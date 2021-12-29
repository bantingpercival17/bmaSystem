(function(jQuery) {
    "use strict";
    if(typeof gsap !== typeof undefined) {
      if (typeof ScrollTrigger !== typeof undefined) {
        gsap.registerPlugin(ScrollTrigger);
      }
  
      const gsapAnimate = {
        onStart : (elem) => {
          const option = {
            opacity: 0,
            scale: 1,
            position: {
              x: 0,
              y:0,
            },
            ease: "",
            duration: 1,
            delay: .4,
            rotate: 0
          }
  
          option.position.x = gsapAnimate.validValue(elem.dataset.iqPositionX, 0)
  
          option.position.y = gsapAnimate.validValue(elem.dataset.iqPositionY, 0)
  
          option.rotate = gsapAnimate.validValue(elem.dataset.iqRotate, 0)
  
          option.scale = gsapAnimate.validValue(elem.dataset.iqScale, 1)
  
          option.opacity = gsapAnimate.validValue(elem.dataset.iqOpacity, 0)
  
          option.delay = gsapAnimate.validValue(elem.dataset.iqDelay, .4)
  
          option.duration = gsapAnimate.validValue(elem.dataset.iqDuration, 1.5)
  
          option.ease = gsapAnimate.validValue(elem.dataset.iqEase, '')
  
          const setOption = {opacity: option.opacity, scale: option.scale, x: option.position.x, y: option.position.y, ease: option.ease, rotate: option.rotate, duration: option.duration, delay: option.delay}
  
          if (typeof ScrollTrigger !== typeof undefined) {
            if (elem.dataset.iqTrigger == 'scroll') {
              const scrub = elem.dataset.iqScrollScrub === 'true' ? true : false
  
              setOption.scrollTrigger = {
                trigger: elem,
                start: () => "top 95%",
                // end: () => "bottom 10%",
                scrub: scrub,
                markers: false
              }
            }
          }
  
          gsap.from(elem, setOption)
  
        },
        validValue: (attr, defaultVal) => {
          if (attr !== undefined && attr !== null) {
            return attr
          }
          return defaultVal
        }
      }
  
      const gsapElem = document.querySelectorAll('[data-iq-gsap="onStart"]')
  
      Array.from(gsapElem, (elem) => {
        gsapAnimate.onStart(elem)
      })

      const t1 = gsap.timeline({defaults: {duration: .6, opacity: 0}, delay: 0})
      t1.from('#hero-title', {y: 10,delay:.60})
      t1.from('#hero-description', {y: 10})
      t1.from('#hero-row',  {y: 10})
      t1.from('#hero-img',  {y: 10, rotate:10, scale:.8})
      console.log('check')

    }
  })(jQuery);