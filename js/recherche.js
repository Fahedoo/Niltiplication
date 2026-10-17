document.getElementById('search').addEventListener('input', function() {
    var query = this.value;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'class/Accueil.class.php?ajax_search=1&q=' + encodeURIComponent(query), true);
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('articles-list').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
});
