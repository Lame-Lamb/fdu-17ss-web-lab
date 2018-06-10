<html>
<head>
    <title>LRC 歌词编辑器</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <style>
        nav ul {
            position: fixed;
            z-index: 99;
            right: 5%;
            border: 1px solid darkgray;
            border-radius: 5px;
            list-style:none;
            padding: 0;
        }

        .tab {
            padding: 1em;
            display: block;
        }

        .tab:hover {
            cursor: pointer;
            background-color: lightgray !important;
        }

        td {
            padding:0.2em;
        }

        textarea[name="edit_lyric"] {
            width: 100%;
            height: 50em;
        }

        input[type="button"] {
            width: 100%;
            height: 100%;
        }

        input[type="submit"] {
            width: 100%;
            height: 100%;
        }

        #td_submit {
            text-align: center;
        }

        select {
            display: block;
            margin: auto;
        }

        #lyric {
            width: 35%;
            height: 60%;
            margin: auto;
            border: 0;
            resize: none;
            font-size: large;
            line-height: 2em;
            text-align: center;
        }

        #player{
            text-align: center;
        }

        div ul{
            list-style:none;
        }

        #lyric #words{
            cursor: default;
            transition: all 0.5s ease;
            width: 100%;
            height: 100%;
            text-align: center;
            font-size: 20px;
        }
        #words li{
            height: 48px;
            line-height: 48px;
        }
        #words .sel{
            color: #3bbaff;
            background: #f1f1f1;
        }
    </style>
</head>
<body>
<nav>
    <ul>
        <li id="d_edit" class="tab">Edit Lyric</li>
        <li id="d_show" class="tab">Show Lyric</li>
    </ul>
</nav>

<!--歌词编辑部分-->
<section id="s_edit" class="content">
    <form id="f_upload" method="post" action="Lab11-form.php" enctype="multipart/form-data">
        <p>请上传音乐文件</p>

        <!--TODO: 在这里补充 html 元素，使 file_upload 上传后若为音乐文件，则可以直接播放-->
        <audio id="music" controls autoplay LOOP>Your browser doesn't support audio.</audio>

        <input type="file" name="file_upload" accept="audio/*">
        <script>
            let fileUpload = document.getElementsByName("file_upload")[0];
            let music = document.getElementById("music");
            fileUpload.addEventListener("change",function () {
                let fReader = new FileReader();
                let file = fileUpload.files[0];
                fReader.readAsDataURL(file);
                fReader.onload = function (oFRevent) {
                    music.src = oFRevent.target.result;
                }
            },false);

        </script>

        <table>
            <tr><td>Title: <input type="text"></td><td>Artist: <input type="text"></td></tr>
            <tr><td colspan="2"><textarea name="edit_lyric" id="edit_lyric"></textarea></td></tr>
            <tr><td><input type="button" id="insert" value="插入时间标签"></td><td><input type="button" id="replace" value="替换时间标签"></td></tr>
            <tr><td colspan="2" id="td_submit"><input type="submit" value="Submit"></td></tr>
        </table>
    </form>
</section>

<!--歌词展示部分-->
<section id="s_show" class="content">
    <form method="post">
    <select id="songName">
        <!--TODO: 在这里补充 html 元素，使点开 #d_show 之后这里实时加载服务器中已有的歌名-->
        <?php
        $dir = "audio/music";
        $i= -1;
        // 打开目录，然后读取其内容
        if (is_dir($dir)){
          if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                if($i > 0){
                    echo "<option value='{$i}'>" . $file . "</option>";
                }
              $i++;
            }
            closedir($dh);
          }
        }
        ?>

    </select>
    </form>

    <!--TODO: 在这里补充 html 元素，使选择了歌曲之后这里展示歌曲进度条，并且支持上下首切换-->
    <br>
    <div id="player">
        <button id="pre">上一首</button>
        <audio id="audio" autoplay controls>Your browser doesn't support audio.</audio>
        <button id="next">下一首</button>
    </div>


    <div id="lyric" readonly="true">
        <ul id="words" style="margin-top: 240px"></ul>
    </div>


</section>
</body>
<script>

    // 界面部分
    document.getElementById("d_edit").onclick = function () {click_tab("edit");};
    document.getElementById("d_show").onclick = function () {click_tab("show");};

    document.getElementById("d_show").click();

    function click_tab(tag) {
        for (let i = 0; i < document.getElementsByClassName("tab").length; i++) document.getElementsByClassName("tab")[i].style.backgroundColor = "transparent";
        for (let i = 0; i < document.getElementsByClassName("content").length; i++) document.getElementsByClassName("content")[i].style.display = "none";

        document.getElementById("s_" + tag).style.display = "block";
        document.getElementById("d_" + tag).style.backgroundColor = "darkgray";
    }

    // Edit 部分
    let edit_lyric_pos = 0;
    document.getElementById("edit_lyric").onmouseleave = function () {
        edit_lyric_pos = document.getElementById("edit_lyric").selectionStart;
    };

    // 获取所在行的初始位置。
    function get_target_pos(n_pos) {
        if (n_pos === undefined) n_pos = edit_lyric_pos;
        let value = document.getElementById("edit_lyric").value;
        let pos = 0;
        for (let i = n_pos; i >= 0; i--) {
            if (value.charAt(i) === '\n') {
                pos = i + 1;
                break;
            }
        }
        return pos;
    }

    // 选中所在行。
    function get_target_line(n_pos) {
        let value = document.getElementById("edit_lyric").value;
        let f_pos = get_target_pos(n_pos);
        let l_pos = 0;

        for (let i = f_pos;; i++) {
            if (value.charAt(i) === '\n') {
                l_pos = i + 1;
                break;
            }
        }
        return [f_pos, l_pos];
    }

    /* HINT:
     * 已经帮你写好了寻找每行开头的位置，可以使用 get_target_pos()
     * 来获取第一个位置，从而插入相应的歌词时间。
     * 在 textarea 中，可以通过这个 DOM 节点的 selectionStart 和
     * selectionEnd 获取相对应的位置。
     *
     * TODO: 请实现你的歌词时间标签插入效果。
     */
    function twoDigit(str) {
        if(str.length === 1){
            str = '0' + str;
            return str;
        }
        if(str.length > 2){
            str = str.substr(0,2);
            return str;
        }
        else{
            return str;
        }
    }

    function getTime() {
        let time = music.currentTime;
        let min = Math.floor(time / 60).toString();
        let sec = Math.floor(time % 60).toString();
        let dec = ((time % 1).toFixed(2) * 100).toString();
        min = twoDigit(min);
        sec = twoDigit(sec);
        dec = twoDigit(dec);
        return "[" + min + ":" + sec + "." + dec + "]";
    }

    //btn-inset&replace
    let insert = document.getElementById("insert");
    insert.addEventListener("click",function () {
        let strTime = getTime();
        let editLyric = document.getElementById("edit_lyric");
        let len = editLyric.value.length;
        let start = get_target_pos(editLyric.focus());
        editLyric.value = editLyric.value.substr(0,start) + strTime + editLyric.value.substr(start,len);
    },false);

    let replace = document.getElementById("replace");
    replace.addEventListener("click",function () {
        let strTime = getTime();
        let editLyric = document.getElementById("edit_lyric");
        let start = get_target_line(editLyric.focus())[0];
        let end = get_target_line(editLyric.focus())[1];
        let oldTimeEnd = -1;
        let len = editLyric.value.length;
        if(editLyric.value.charAt(start) !== '[')   return;
        for(let i = start;i < end;i++){
            if(editLyric.value.charAt(i) === ']'){
                oldTimeEnd = i;
            }
        }
        if(oldTimeEnd !== -1){
            editLyric.value = editLyric.value.substr(0,start) + strTime + editLyric.value.substr(oldTimeEnd + 1,len)
        }
    },false);

    //select
    let select = document.getElementsByTagName("select")[0];
    select.addEventListener("change",function () {
        let index = select.selectedIndex;
        let audio = document.getElementById("audio");
        audio.setAttribute("src","audio/music/" + select.options[index].text);
        displayLrc();
    },false);

    //btnPre&btnNext
    let btnPre = document.getElementById("pre");
    btnPre.addEventListener("click",function () {
        let index = select.selectedIndex;
        if(index > 0){
            index--;
            select.selectedIndex = index;
            let audio = document.getElementById("audio");
            audio.setAttribute("src","audio/music/" + select.options[index].text);
            displayLrc();
        }
    },false);

    let btnNext = document.getElementById("next");
    btnNext.addEventListener("click",function () {
        let index = select.selectedIndex;
        if(index < select.options.length - 1){
            index++;
            select.selectedIndex = index;
            let audio = document.getElementById("audio");
            audio.setAttribute("src","audio/music/" + select.options[index].text);
            displayLrc();
        }
    },false);


    /* TODO: 请实现你的上传功能，需包含一个音乐文件和你写好的歌词文本。
     */

    /* HINT:
     * 实现歌词和时间的匹配的时候推荐使用 Map class，ES6 自带。
     * 在 Map 中，key 的值必须是字符串，但是可以通过字符串直接比较。
     * 每一行行高可粗略估计为 40，根据电脑差异或许会有不同。
     * 当前歌词请以粗体显示。
     * 从第八行开始，当歌曲转至下一行的时候，需要调整滚动条，使得当前歌
     * 词保持在正中。
     *
     * TODO: 请实现你的歌词滚动效果。
     */

    function displayLrc() {
        let index = select.selectedIndex;
        htmlobj = $.ajax({url:"audio/lyric/" + select.options[index].text + ".lrc",async:false});
        let words = document.getElementById("words");
        while(words.hasChildNodes()){
            words.removeChild(words.firstChild);
        }
        showLyric();
    }

    function parseLyric(text) {
        let lyric = text.split('\r\n');
        return lyric;
    }

    //roll
    function showLyric () {
        let formatTime = function(time){
            let m = time.split(':')[0];
            let s = time.split(':')[1];
            return Number(m) * 60 + Number(s);
        };

        let lyricArray = [];
        let index = select.selectedIndex;
        lyricArray[0] = {time:0,lyric:select.options[index].text};
        for(let i = 0;i < parseLyric(htmlobj.responseText).length;i++){
            let tmpTime = /\d+:\d+.\d+/.exec(parseLyric(htmlobj.responseText)[i]);
            let tmpLyric = parseLyric(htmlobj.responseText)[i].split(/[\\[]\d+:\d+.\d+]/);
            if(tmpTime!=null){
                lyricArray.push({time:formatTime(String(tmpTime)),lyric:tmpLyric[1]});
            }
        }


        for(let i=0 ; i < lyricArray.length;i++){
            let lyricBorder = document.getElementById('words');
            let lyricEl = document.createElement('li');
            lyricEl.innerHTML = lyricArray[i].lyric;
            lyricBorder.appendChild(lyricEl);
        }

        let audio = document.getElementById('audio');
        let count = 0;

        let vaildTime = function(time,index){
            console.log(index,lyricArray.length);
            if(index < lyricArray.length-1){
                if(time >= lyricArray[index].time && time <= lyricArray[index + 1].time){
                    return true;
                }else{
                    return false;
                }
            }else{
                if(time <= audio.duration){
                    return true;
                }else{
                    return false;
                }
            }

        };

        let wordEl = document.getElementById('words');
        let marTop = 240;

        audio.ontimeupdate = function(){
            let time = audio.currentTime;
            if(!vaildTime(time,count)) {
                count++;
            }
            wordEl.style.marginTop = (marTop - count * 48) + 'px';
            let li = wordEl.querySelectorAll('li');
            for(let i = 0 ; i < li.length ; i++){
                li[i].removeAttribute('class');
            }
            wordEl.querySelectorAll('li')[count].setAttribute('class','sel');
            if(audio.ended){
                wordEl.style.marginTop = marTop + 'px';
                count = 0;
            }
        }
        audio.onseeked = function(){
            let cur_time = audio.currentTime;
            for(let _i = 0;_i <= lyricArray.length - 1;_i++){
                if (cur_time >= lyricArray[_i].time && cur_time <= lyricArray[_i + 1].time)
                    count = _i;
            }
        }
    }

</script>
</html>
