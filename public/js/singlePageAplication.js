var links = document.querySelectorAll('a');


function updateLinks() {

    links.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();


            getNewPage(link.href);
        });
    })
}

updateLinks();

function getNewPage(url) {
    const params = new URLSearchParams();
    params.append('singlePageApplication', 'true');


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
}


function updatePage(jsonResponse) {
    const mainContent = document.querySelector('main');
    if (mainContent) {
        mainContent.innerHTML = jsonResponse.body;
    }

    const currentScripts = document.querySelectorAll('script');
    currentScripts.forEach(script => script.remove());

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

    const currentStyles = document.querySelectorAll('link[rel="stylesheet"]');
    currentStyles.forEach(style => style.remove());

    jsonResponse.styles.forEach(style => {
        const newStyle = document.createElement('link');
        newStyle.rel = 'stylesheet';
        newStyle.href = style.href;
        document.head.appendChild(newStyle);
    });

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

    updateLinks();
}