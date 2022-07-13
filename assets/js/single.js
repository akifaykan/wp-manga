class Mangas {
    constructor(){
        this.main = document.querySelector('.main')
        this.images = document.querySelector('.imgs')
        this.numbers = document.querySelector('.numbers')
        this.uri = this.main.getAttribute('data-url')
        this.episode = this.main.getAttribute('data-episode')
        this.volume = this.main.getAttribute('data-volume')
        this.storage = `berserk${this.volume}`

        this.imgLoop(this.episode)
        this.loadStorage()
        this.observAction()
        this.numbers.addEventListener('click', e => this.numbersAction(e))
    }
    
    imgLoop(episode){
        for (let i = 0; i < episode; i++){
            const num = (i < 10) ? `0${i}` : i
            this.images.innerHTML += this.templateImg(num)
            this.numbers.innerHTML += this.templateNumber(num)
        }
    }
    
    numbersAction(e){
        const el = e.target
        if (el.classList.contains('imglink')){
            const id = el.getAttribute('href')
            const clear = this.numbers.querySelectorAll('.imglink')
            
            this.clearClasses(clear)
            localStorage.setItem(this.storage, id)
            el.classList.add('active')
        }
    }

    loadStorage(){
        let local = localStorage.getItem(this.storage)
        if (local) {
            setTimeout(()=> {
                let link = document.querySelector(`[href="${local}"]`)
                link.click()
                link.scrollIntoView({ block: "center", behavior: 'smooth' })
            }, 100)
        }
    }
    
    observAction(){
        const callback = (entries) =>{
            entries.forEach(entry =>{
                if (entry.isIntersecting){
                    const id = entry.target.id
                    const el = document.querySelector(`[href="#${id}"]`)
                    el.classList.add('taret')
                    el.scrollIntoView({ block: "center", behavior: 'smooth' })
                }
            })
        }
        const options = { rootMargin: "-10px" }
        const observer = new IntersectionObserver(callback, options)
        const images = document.querySelectorAll(`div[id*="img"]`)
        images.forEach(img => observer.observe(img))
    }

    templateImg(num){
        return `
        <div id="img${num}">
            <img class="img lazyload"
            src="${this.uri}/assets/img/noimg.svg"
            data-src="${this.uri}/ciltler/berserk-${this.volume}/${num}.jpg" />
        </div>
        `
    }

    templateNumber(num){
        return `<a class="imglink" href="#img${num}">&rarr; Bölüm ${num}</a>`
    }
    
    clearClasses(clear){
        clear.forEach(item => item.classList.remove('active'))
    }
}

document.addEventListener('DOMContentLoaded', ()=>{
    new Mangas()
})