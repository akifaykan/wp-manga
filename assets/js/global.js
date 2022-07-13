document.addEventListener('DOMContentLoaded', ()=>{
    let rtop = document.getElementById('rtop')
    window.addEventListener('scroll', function() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20){
            rtop.style.display = 'block'
        } else {
            rtop.style.display = 'none'
        }
    })
    rtop.addEventListener('click', function() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    })
})
