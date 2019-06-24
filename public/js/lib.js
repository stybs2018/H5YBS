
//  页面POST
var PostForm = function (url, token, params)
{
    let form = document.createElement("form");
    form.action = url;
    form.method = 'POST';
    form.style.display = "none";
    
    if (token.length) {
        var opt = document.createElement('input');
        opt.name = '_token';
        opt.value = token;
        form.appendChild(opt)
    }
    
    Object.keys(params).forEach(function(key) {
        var opt = document.createElement('input');
        opt.name = key;
        opt.value = params[key];
        form.appendChild(opt);
    })
    document.body.appendChild(form);
    form.submit();
}