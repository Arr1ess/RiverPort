const delete_blok_btns = Array.from(document.querySelectorAll('[data-delete_block]'));

delete_blok_btns.map((delete_btn) => {
    const pazle_name = delete_btn.getAttribute('data-pazle_name');
    const block_id = delete_btn.getAttribute('data-delete_block');
    delete_btn.addEventListener('click', (event) => {
        event.preventDefault();
        delete_block_from_pazl(pazle_name, block_id);
    })
})

function delete_block_from_pazl(pazle_name, block_id) {
    fetch("/rBlock/pazl", {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        pazle_name: pazle_name,
        block_id: block_id
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}