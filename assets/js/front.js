document.addEventListener('DOMContentLoaded', ()=>{
    const list = document.querySelectorAll('.manga_list li')
    if (!list) return
    list.forEach(item => {
        const volume = item.getAttribute('data-volume')
        let episode = item.getAttribute('data-episode')
        const select = document.querySelector(`[data-volume="${volume}"] .title`)
        const locale = localStorage.getItem(`berserk${volume}`)
        
        if (locale === null) return
        if (episode === locale) {
            select.classList.add('read_completed')
        } else {
            select.classList.add('read')
        }
    })
})
