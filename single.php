<?php

get_header();

$cilt = get_field('cilt');
$bolum = get_field('bolum');

?>
<main>
    <div class="numbers"></div>
    <div class="single__container imgs"></div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', ()=>{
        const imgs = document.querySelector('.imgs')
        const numbers = document.querySelector('.numbers')

        if (!imgs) return

        for (let i = 0; i < <?=$bolum?>; i++){
            const lent = (i < 10) ? `0${i}` : i
            imgs.innerHTML += `<div id="img${lent}"><img class="img lazyload" src="data:image/svg+xml,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20width='965'%20height='1400'%20viewBox%3D'0%200%20360%20320'%2F%3E" data-src="<?=K_URI?>/ciltler/berserk-<?=$cilt?>/${lent}.jpg" /></div>`

            numbers.innerHTML += `<a class="imglink" href="#img${lent}">&rarr; Bölüm ${lent}</a>`
        }

        numbers.addEventListener('click', (e)=>{
            if (e.target.classList.contains('imglink')){
                clearClass()
                const link = e.target.getAttribute('href')
                document.querySelector(`[href="${link}"]`).classList.add('active')
                localStorage.setItem("berserk<?=$cilt?>", link)
            }
        })

        function clearClass(){
            const clear = numbers.querySelectorAll('.imglink')
            clear.forEach(item => item.classList.remove('active'))
        }
    })
    window.addEventListener('load', ()=>{
        let local = localStorage.getItem("berserk<?=$cilt?>")
        if (local) {
            setTimeout(()=> {
                let link = document.querySelector(`[href="${local}"]`)
                link.click()
                link.scrollIntoView({ block: "center", behavior: 'smooth' })
            }, 100)
        }

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
        const options = {
            rootMargin: "-10px"
        }
        const observer = new IntersectionObserver(callback, options)
        const images = document.querySelectorAll(`div[id*="img"]`)
        images.forEach(img =>{
            observer.observe(img)
        })
    })
</script>
<?php

get_footer();