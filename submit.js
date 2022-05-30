function submit(url, id){

    var form = document.createElement("form");
    form.setAttribute("charset", "UTF-8");
    form.setAttribute("method", "Post");  //Post 방식
    form.setAttribute("action", url); //요청 보낼 주소

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "loginid");
    hiddenField.setAttribute("value", id);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
}

function submit2(url, id1, id2){

    var form = document.createElement("form");
    form.setAttribute("charset", "UTF-8");
    form.setAttribute("method", "Post");  //Post 방식
    form.setAttribute("action", url); //요청 보낼 주소

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "loginid");
    hiddenField.setAttribute("value", id1);
    form.appendChild(hiddenField);

    hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "aid");
    hiddenField.setAttribute("value", id2);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
}

function gouser(url, id1, id2){

    var form = document.createElement("form");
    form.setAttribute("charset", "UTF-8");
    form.setAttribute("method", "Post");  //Post 방식
    form.setAttribute("action", url); //요청 보낼 주소

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "loginid");
    hiddenField.setAttribute("value", id1);
    form.appendChild(hiddenField);

    hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "visitid");
    hiddenField.setAttribute("value", id2);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
}

function delreply(url, id1, id2, rid){

    
    if(confirm('정말 댓글을 삭제할 건가요?')){

        var form = document.createElement("form");
        form.setAttribute("charset", "UTF-8");
        form.setAttribute("method", "Post");  //Post 방식
        form.setAttribute("action", url); //요청 보낼 주소

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "loginid");
        hiddenField.setAttribute("value", id1);
        form.appendChild(hiddenField);

        hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "aid");
        hiddenField.setAttribute("value", id2);
        form.appendChild(hiddenField);

        hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "rem_reply");
        hiddenField.setAttribute("value", rid);
        form.appendChild(hiddenField);

        document.body.appendChild(form);
        form.submit();
    }

}