/*
* Version: 1.0.0
* Template: GIGZ
* Author: iqonic.design
* Design and Developed by: iqonic.design
* NOTE: This file contains the script for initialize & listener Template.
*/

/*----------------------------------------------
Index Of Script
------------------------------------------------

------- Plugin Init --------

:: Tooltip
:: Popover
:: Circle Progress
:: NoUiSlider
:: CopyToClipboard
:: Vanila Datepicker
:: SliderTab
:: Data Tables
:: Active Class for Pricing Table
:: Active Class for Header

------ Functions --------

:: Loader Init
:: Resize Plugins
:: Landing page js
:: Sidebar Toggle
:: Back To Top

------- Listners ---------

:: DOMContentLoaded
:: Window Resize
------------------------------------------------
Index Of Script
----------------------------------------------*/
"use strict";
/*---------------------------------------------------------------------
              Popover
-----------------------------------------------------------------------*/

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
if(typeof bootstrap !== typeof undefined) {
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
  })
}

/*---------------------------------------------------------------------
                Tooltip
-----------------------------------------------------------------------*/

if(typeof bootstrap !== typeof undefined) {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
  
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-sidebar-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
}



/*---------------------------------------------------------------------
              Circle Progress
-----------------------------------------------------------------------*/

const progressBar = document.getElementsByClassName('circle-progress')
if(typeof progressBar !== typeof undefined) {
  Array.from(progressBar, (elem) => {
    const minValue = elem.getAttribute('data-min-value')
    const maxValue = elem.getAttribute('data-max-value')
    const value = elem.getAttribute('data-value')
    const  type = elem.getAttribute('data-type')
    if (elem.getAttribute('id') !== '' && elem.getAttribute('id') !== null) {
      new CircleProgress('#'+elem.getAttribute('id'), {
        min: minValue,
        max: maxValue,
        value: value,
        textFormat: type,
    });
    }
  })
}

/*---------------------------------------------------------------------
Progress Bar
-----------------------------------------------------------------------*/
const progressBarInit = (elem) => {
  const currentValue = elem.getAttribute('aria-valuenow')
  elem.style.width = '0%'
  elem.style.transition = 'width 2s'
  if (typeof Waypoint !== typeof undefined) {
    new Waypoint( {
      element: elem,
      handler: function() { 
        setTimeout(() => {
          elem.style.width = currentValue + '%'
        }, 100);
      },
      offset: 'bottom-in-view',
    })
  }
}

const customProgressBar = document.querySelectorAll('[data-toggle="progress-bar"]')
Array.from(customProgressBar, (elem) => {
  progressBarInit(elem)
})

/*---------------------------------------------------------------------
                 noUiSlider
-----------------------------------------------------------------------*/

const rangeSlider = document.querySelectorAll('.range-slider');

Array.from(rangeSlider, (elem) => {
  if(typeof noUiSlider !== typeof undefined) {
    noUiSlider.create(elem, {
      start: [20, 80],
      connect: true,
      range: {
          'min': 0,
          'max': 100
      }
    })
  }
})

const slider = document.querySelectorAll('.slider');

Array.from(slider, (elem) => {
  if(typeof noUiSlider !== typeof undefined) {
    noUiSlider.create(elem, {
      start: 50,
      connect: [true, false],
      range: {
          'min': 0,
          'max': 100
      }
    })
  }
})

/*---------------------------------------------------------------------
              Copy To Clipboard
-----------------------------------------------------------------------*/
const copy = document.querySelectorAll('[data-toggle="copy"]')
if(typeof copy !== typeof undefined) {
  Array.from(copy, (elem) => {
    elem.addEventListener('click', (e) => {
      const target = elem.getAttribute("data-copy-target");
      let value = elem.getAttribute("data-copy-value");
      const container = document.querySelector(target);
      if (container !== undefined && container !== null) {
        if (container.value !== undefined && container.value !== null) {
          value = container.value;
        } else {
          value = container.innerHTML;
        }
      }
      if (value !== null) {
        const elem = document.createElement("input");
        document.querySelector("body").appendChild(elem);
        elem.value = value;
        elem.select();
        document.execCommand("copy");
        elem.remove();
      }
    })
  });
}

/*---------------------------------------------------------------------
              Vanila Datepicker
-----------------------------------------------------------------------*/
const datepickers = document.querySelectorAll('.vanila-datepicker')
Array.from(datepickers, (elem) => {
  if(typeof Datepicker !== typeof undefined) {
    new Datepicker(elem)
  }
})
const daterangePickers = document.querySelectorAll('.vanila-daterangepicker')
Array.from(daterangePickers, (elem) => {
  if(typeof Datepicker !== typeof undefined) {
    new DateRangePicker(elem)
  }
})

/*---------------------------------------------------------------------
              CounterUp 2
-----------------------------------------------------------------------*/
if (window.counterUp !== undefined) {
  const counterUp = window.counterUp["default"];
  const counterUp2 = document.querySelectorAll( '.counter' )
  Array.from(counterUp2, (el) => {
    if (typeof Waypoint !== typeof undefined) {
      const waypoint = new Waypoint({
        element: el,
        handler: function () {
          counterUp(el, {
            duration: 1000,
            delay: 10,
          });
          this.destroy();
        },
        offset: "bottom-in-view",
      });
    }
  })
}

/*---------------------------------------------------------------------
              SliderTab
-----------------------------------------------------------------------*/

Array.from(document.querySelectorAll('[data-toggle="slider-tab"]'), (elem) => {
  if (typeof SliderTab !== typeof undefined) {
    new SliderTab(elem)
  }
})

let Scrollbar
if (typeof Scrollbar !== typeof null) {
  if (document.querySelectorAll(".data-scrollbar").length) {
    Scrollbar = window.Scrollbar
    Scrollbar.init(document.querySelector('.data-scrollbar'), {
      continuousScrolling: false,
    })
  }
}

/*---------------------------------------------------------------------
  Data tables
-----------------------------------------------------------------------*/
if($.fn.DataTable){
  if($('[data-toggle="data-table"]').length) {
    const table = $('[data-toggle="data-table"]').DataTable({
      "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
    });
  }
}

/*---------------------------------------------------------------------
  Active Class for Pricing Table
-----------------------------------------------------------------------*/
jQuery("#my-table tr th").on('click',function () {
  jQuery('#my-table tr th').children().removeClass('active');
  jQuery(this).children().addClass('active');
  jQuery("#my-table td").each(function () {
    if (jQuery(this).hasClass('active')) {
      jQuery(this).removeClass('active')
    }
  });
  var col = jQuery(this).index();
  jQuery("#my-table tr td:nth-child(" + parseInt(col + 1) + ")").addClass('active');
});

/*---------------------------------------------------------------------
  Active Class for Header
-----------------------------------------------------------------------*/
const header = document.querySelector('.mobile-menu')
    if (header !== null) {
    const headerActiveItem = header.querySelectorAll('.active')
    Array.from(headerActiveItem, (elem) => {
      if (elem.closest('li') !== null){
          const childMenu = elem.closest('ul').parentElement.getElementsByTagName('a')[0]
          childMenu.classList.add('active')
      }
  })
}

/*---------------------------------------------------------------------
              Resize Plugins
-----------------------------------------------------------------------*/

const resizePlugins = () => {
  // sidebar-mini
  const tabs = document.querySelectorAll('.nav')
  const sidebarResponsive = document.querySelector('.sidebar-default')
  if (window.innerWidth < 1025) {
    Array.from(tabs, (elem) => {
      if (!elem.classList.contains('flex-column') && elem.classList.contains('nav-tabs') && elem.classList.contains('nav-pills')) {
        elem.classList.add('flex-column', 'on-resize');
      }
    })
    if(sidebarResponsive !== null) {
      if (!sidebarResponsive.classList.contains('sidebar-mini')) {
        sidebarResponsive.classList.add('sidebar-mini','on-resize')
      }
    }
  } else {
    Array.from(tabs, (elem) => {
      if (elem.classList.contains('on-resize')) {
        elem.classList.remove('flex-column', 'on-resize');
      }
    })
    if(sidebarResponsive !== null) {
      if (sidebarResponsive.classList.contains('sidebar-mini') && sidebarResponsive.classList.contains('on-resize')) {
        sidebarResponsive.classList.remove('sidebar-mini','on-resize')
      }
    }
  }
}
/*---------------------------------------------------------------------
              Landing page js
-----------------------------------------------------------------------*/

const urlParams = new URLSearchParams(window.location.search);
const myParam = urlParams.get('id');
const header11 = document.querySelector('#head-check')

if (header11 !== null) {
  const headerActiveItem = header11.querySelectorAll('.nav-link')
  if(myParam !== null){
    Array.from(headerActiveItem, (elem) => {
      document.getElementById(myParam).classList.add('active')
    })
    if(document.getElementById(myParam)){
      const pageHeader =  document.getElementById(myParam).innerHTML
      document.getElementById("head-data").innerHTML = pageHeader;
    }
  }
}

/*---------------------------------------------------------------------
              LoaderInit
-----------------------------------------------------------------------*/

const loaderInit = () => {
  const loader = document.querySelector('.loader')
  const timer = 250
  setTimeout(() => {
    loader.classList.add('animate__animated', 'animate__fadeOut')
    setTimeout(() => {
      loader.classList.add('d-none')
    }, timer * .1)
  }, timer * 1)
}

/*---------------------------------------------------------------------
              Sidebar Toggle
-----------------------------------------------------------------------*/
const sidebarToggle = (elem) => {
  elem.addEventListener('click', (e) => {
    const sidebar = document.querySelector('.sidebar')
    if (sidebar.classList.contains('sidebar-mini')) {
      sidebar.classList.remove('sidebar-mini')
    } else {
      sidebar.classList.add('sidebar-mini')
    }
  })
}

const sidebarToggleBtn = document.querySelectorAll('[data-toggle="sidebar"]')
const sidebar = document.querySelector('.sidebar-default')
if (sidebar !== null) {
  const sidebarActiveItem = sidebar.querySelectorAll('.active')
  Array.from(sidebarActiveItem, (elem) => {
    if (!elem.closest('ul').classList.contains('iq-main-menu')) {
      const childMenu = elem.closest('ul')
      childMenu.classList.add('show')
      const parentMenu = childMenu.closest('li').querySelector('.nav-link')
      parentMenu.classList.add('collapsed')
      parentMenu.setAttribute('aria-expanded', true)
    }
  })
}
Array.from(sidebarToggleBtn, (sidebarBtn) => {
  sidebarToggle(sidebarBtn)
})

/*------------------------
Back To Top
--------------------------*/

const backToTop = document.querySelector('#back-to-top')
if(backToTop !== null) {
  backToTop.classList.add('animate__animated','animate__fadeOut')
  
  window.addEventListener('scroll', (e) => {
    if (document.documentElement.scrollTop > 250) {
      backToTop.classList.remove('animate__fadeOut')
      backToTop.classList.add('animate__fadeIn')
    }else {
      backToTop.classList.remove('animate__fadeIn')
      backToTop.classList.add('animate__fadeOut')
    }
  })
}

// scroll body to 0px on click
const topBtn = document.querySelector('#top')
if(topBtn !== null){
  topBtn.addEventListener('click', (e) => {
    e.preventDefault()
    window.scrollTo({top: 0, behavior: 'smooth'});
  })
}

/*---------------------------------------------------------------------
              DOMContentLoaded
-----------------------------------------------------------------------*/
setTimeout(function () {
  resizePlugins()
  loaderInit()
}, 0)

/*---------------------------------------------------------------------
              Window Resize
-----------------------------------------------------------------------*/

window.addEventListener('resize', function(event) {
  resizePlugins()
});

/*-------------------------------
| | | | | DropDown
--------------------------------*/

function darken_screen(yesno){
  if( yesno == true ){
    if (document.querySelector('.screen-darken') !== null) {
      document.querySelector('.screen-darken').classList.add('active');
    }
  }
  else if(yesno == false){
    if (document.querySelector('.screen-darken') !== null) {
      document.querySelector('.screen-darken').classList.remove('active');
    }
  }
}
	
function close_offcanvas(){
  darken_screen(false);
  if (document.querySelector('.mobile-offcanvas.show') !== null) {
    document.querySelector('.mobile-offcanvas.show').classList.remove('show');
    document.body.classList.remove('offcanvas-active');
  }
}

function show_offcanvas(offcanvas_id){
  darken_screen(true);
  if(document.getElementById(offcanvas_id) !== null) {
    document.getElementById(offcanvas_id).classList.add('show');
    document.body.classList.add('offcanvas-active');
  }
}

document.addEventListener("DOMContentLoaded", function(){
  document.querySelectorAll('[data-trigger]').forEach(function(everyelement){
    let offcanvas_id = everyelement.getAttribute('data-trigger');
    everyelement.addEventListener('click', function (e) {
      e.preventDefault();
          show_offcanvas(offcanvas_id);
    });
  });
  if(document.querySelectorAll('.btn-close')) {
    document.querySelectorAll('.btn-close').forEach(function(everybutton){
      everybutton.addEventListener('click', function (e) { 
            close_offcanvas();
        });
    });
  }
  if(document.querySelector('.screen-darken')) {
    document.querySelector('.screen-darken').addEventListener('click', function(event){
      close_offcanvas();
    });
  }
});
if (document.querySelector('#navbarSideCollapse'))  {
  document.querySelector('#navbarSideCollapse').addEventListener('click', function () {
    document.querySelector('.offcanvas-collapse').classList.toggle('open')
  })
}

/*---------------------------------------------------------------------
Form Validation
-----------------------------------------------------------------------*/

// Example starter JavaScript for disabling form submissions if there are invalid fields
window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
}, false);

/*---------------------------------------------------------------------
swiper
-----------------------------------------------------------------------*/
var swiper = new Swiper(".mySwiper", {
  slidesPerView: 5,
  spaceBetween: 10,
  slidesPerGroup: 5,
  loop: true,
  loopFillGroupWithBlank: true,
  navigation: {
    nextEl: ".swiper-button-next1",
    prevEl: ".swiper-button-prev1",
  },
  breakpoints: {
    320: { slidesPerView: 2 },
    550: { slidesPerView: 3 },
    991: { slidesPerView: 5 },
    1400: { slidesPerView: 5 },
    1500: { slidesPerView: 5 },
    1920: { slidesPerView: 5 },
},
});