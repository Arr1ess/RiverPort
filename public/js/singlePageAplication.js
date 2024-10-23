// Найти все элементы <a>
const links = document.querySelectorAll('a');

// Добавить обработчик события click к каждой ссылке
links.forEach(link => {
    link.addEventListener('click', function(event) {
        // Отключить стандартное поведение
        event.preventDefault();
        
        // Вывести в консоль URL, на который указывает ссылка
        getNewPage(link.href);
    });
});


function getNewPage(url) {
    const params = new URLSearchParams();
    params.append('singlePageApplication', 'true');

    // Добавляем параметры к URL
    const fullUrl = `${url}?${params.toString()}`;

    fetch(fullUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        changeUrl(url);
        updatePage(data);
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}

 
function changeUrl(newUrl) {
    history.pushState(null, '', newUrl);
    console.log('URL changed to:', newUrl);
}


function updatePage(jsonResponse) {
    // Обновляем основное содержимое страницы
    const mainContent = document.querySelector('main');
    if (mainContent) {
        mainContent.innerHTML = jsonResponse.body;
    }

    // Удаляем все текущие скрипты
    const currentScripts = document.querySelectorAll('script');
    currentScripts.forEach(script => script.remove());

    // Добавляем новые скрипты
    jsonResponse.scripts.forEach(script => {
        const newScript = document.createElement('script');
        newScript.src = script.src;
        if (script.attributes && script.attributes.length > 0) {
            script.attributes.forEach(attr => {
                const [name, value] = attr.split('=');
                newScript.setAttribute(name, value || '');
            });
        }
        document.body.appendChild(newScript);
    });

    // Удаляем все текущие стили
    const currentStyles = document.querySelectorAll('link[rel="stylesheet"]');
    currentStyles.forEach(style => style.remove());

    // Добавляем новые стили
    jsonResponse.styles.forEach(style => {
        const newStyle = document.createElement('link');
        newStyle.rel = 'stylesheet';
        newStyle.href = style.href;
        document.head.appendChild(newStyle);
    });

    // Добавляем новые SEO теги
    jsonResponse.seoTags.forEach(tag => {
        const newTag = document.createElement(tag.tagName);
        if (tag.attributes && tag.attributes.length > 0) {
            tag.attributes.forEach(attr => {
                const [name, value] = attr.split('=');
                newTag.setAttribute(name, value || '');
            });
        }
        document.head.appendChild(newTag);
    });
}