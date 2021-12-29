(function (jQuery) {
  "use strict";
  "use strict";
  let lastDate = 0
  let TICKINTERVAL = 864e5;
  let XAXISRANGE = 7776e5;
  const chartFunction = {
      chartDummySearies: (e, t, refData) => {
          let data = refData
          const a = e + TICKINTERVAL;
          lastDate = a;
          for (let n = 0; n < data.length - 50; n++) data[n].x = a - XAXISRANGE - TICKINTERVAL, data[n].y = 0;
          data.push({
              x: a,
              y: Math.floor(Math.random() * (t.max - t.min + 1)) + t.min
          })
          return data
      },
      generateDayWiseTimeSeries(baseval, count, yrange) {
          let i = 0;
          let series = [];
          while (i < count) {
              let x = baseval;
              let y =
                  Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

              series.push([x, y]);
              baseval += 86400000;
              i++;
          }
          return series;
      }
  }

if (jQuery('#d-activity').length) {
    const options = {
      series: [{
        name: 'Successful deals',
        data: [30, 50, 35, 60, 40, 60, 60, 30, 50, 35,]
      }, {
        name: 'Failed deals',
        data: [40, 50, 55, 50, 30, 80, 30, 40, 50, 55]
      }],
      chart: {
        type: 'bar',
        height: 230,
        stacked: true,
        toolbar: {
            show:false
          }
      },
      colors: ["#3a57e8", "#4bc7d2"],
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '28%',
          endingShape: 'rounded',
          borderRadius: 5,
        },
      },
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: ['S', 'M', 'T', 'W', 'T', 'F', 'S', 'M', 'T', 'W'],
        labels: {
          minHeight:20,
          maxHeight:20,
          style: {
            colors: "#8A92A6",
          },
        }
      },
      yaxis: {
        title: {
          text: ''
        },
        labels: {
            minWidth: 19,
            maxWidth: 19,
            style: {
              colors: "#8A92A6",
            },
        }
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return "$ " + val + " thousands"
          }
        }
      }
    };
  
    const chart = new ApexCharts(document.querySelector("#d-activity"), options);
    chart.render();
  }

if ($('.d-slider1').length > 0) {
    const options = {
        centeredSlides: false,
        loop: false,
        slidesPerView: 4,
        autoplay:false,
        spaceBetween: 32,
        breakpoints: {
            320: { slidesPerView: 1 },
            550: { slidesPerView: 2 },
            991: { slidesPerView: 3 },
            1400: { slidesPerView: 4 },
            1500: { slidesPerView: 5 },
            1920: { slidesPerView: 6 },
            2040: { slidesPerView: 7 },
            2440: { slidesPerView: 8 }
        },
        pagination: {
            el: '.swiper-pagination'
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },  

        // And if we need scrollbar
        scrollbar: {
            el: '.swiper-scrollbar'  
        }
    } 
    let swiper = new Swiper('.d-slider1',options);

    document.addEventListener('ChangeMode', (e) => {
      if (e.detail.rtl === 'rtl' || e.detail.rtl === 'ltr') {
        swiper.destroy(true, true)
        setTimeout(() => {
            swiper = new Swiper('.d-slider1',options);
        }, 500);
      }
    })
}
if (jQuery('.d-slider5').length > 0) {
  const options = {
    centeredSlides: false,
    loop: false,
    slidesPerView: 6,
    autoplay:false,
    spaceBetween: 32,
    grid: {
      rows: 1,
    },
    breakpoints: {
      320: { slidesPerView: 1 },
      550: { slidesPerView: 2 },
      991: { slidesPerView: 3 },
      1400: { slidesPerView: 3 },
      1500: { slidesPerView: 4},
      1920: { slidesPerView: 4 },
      2040: { slidesPerView: 4},
      2440: { slidesPerView: 4}
  },
    pagination: {
        el: '.swiper-pagination'
    },
    // And if we need scrollbar
    scrollbar: {
        el: '.swiper-scrollbar'  
    }
}  
  new Swiper('.d-slider5',options);
}




if (jQuery("#admin-chart-01").length) {
  let data = []
  const options = {
      series: [{
          name: 'Food',
          data: data.slice()
      }],
      colors: ["#7B60E7"],
      stroke: {
          width: 2
      },
      chart: {
          height: 60,
          type: 'area',
          animations: {
              enabled: true,
              easing: 'linear',
              dynamicAnimation: {
                  speed: 1e3
              }
          },
          sparkline: {
              enabled: true,
          }
      },
      dataLabels: {
          enabled: false
      },

      xaxis: {
          type: 'datetime',
          range: XAXISRANGE,
      },
      yaxis: {
          max: 100
      },
      fill: {
          type: 'gradient',
          gradient: {
              opacityFrom: 0.91,
              opacityTo: 0.2,
          }
      },
  };

  const chart = new ApexCharts(document.querySelector("#admin-chart-01"), options);
  chart.render();

  setInterval(() => {
      data = chartFunction.chartDummySearies(lastDate, {
          min: 10,
          max: 90
      }, data)
      chart.updateSeries([{
          'data': data
      }])
  }, 1e3)

}
if (jQuery("#admin-chart-02").length) {
  let data = []
  const options = {
      series: [{
          name: 'Food',
          data: data.slice()
      }],
      colors: ["#FF9BA3"],
      stroke: {
          width: 2
      },
      chart: {
          height: 60,
          type: 'area',
          animations: {
              enabled: true,
              easing: 'linear',
              dynamicAnimation: {
                  speed: 1e3
              }
          },
          sparkline: {
              enabled: true,
          }
      },
      dataLabels: {
          enabled: false
      },

      xaxis: {
          type: 'datetime',
          range: XAXISRANGE,
      },
      yaxis: {
          max: 100
      },
      fill: {
          type: 'gradient',
          gradient: {
              opacityFrom: 0.91,
              opacityTo: 0.2,
          }
      },
  };

  const chart = new ApexCharts(document.querySelector("#admin-chart-02"), options);
  chart.render();

  setInterval(() => {
      data = chartFunction.chartDummySearies(lastDate, {
          min: 10,
          max: 90
      }, data)
      chart.updateSeries([{
          'data': data
      }])
  }, 1e3)

}
if (jQuery("#admin-chart-03").length) {
  let data = []
  const options = {
      series: [{
          name: 'Food',
          data: data.slice()
      }],
      colors: ["#4FC280"],
      stroke: {
          width: 2
      },
      chart: {
          height: 60,
          type: 'area',
          animations: {
              enabled: true,
              easing: 'linear',
              dynamicAnimation: {
                  speed: 1e3
              }
          },
          sparkline: {
              enabled: true,
          }
      },
      dataLabels: {
          enabled: false
      },

      xaxis: {
          type: 'datetime',
          range: XAXISRANGE,
      },
      yaxis: {
          max: 100
      },
      fill: {
          type: 'gradient',
          gradient: {
              opacityFrom: 0.91,
              opacityTo: 0.2,
          }
      },
  };

  const chart = new ApexCharts(document.querySelector("#admin-chart-03"), options);
  chart.render();

  setInterval(() => {
      data = chartFunction.chartDummySearies(lastDate, {
          min: 10,
          max: 90
      }, data)
      chart.updateSeries([{
          'data': data
      }])
  }, 1e3)

}
if (jQuery('#admin-chart-04').length) {
  const options = {
      series: [{
          name: 'Marketing Sales',
          data: [58, 80, 85, 80, 70, 75, 85, 80, 79, 90, 89, 75]
      }, {
          name: 'Cases Sales',
          data: [65, 63, 68, 71, 73, 76, 65, 62, 70, 69, 67, 60]
      }],
      chart: {
          fontFamily: '"Inter", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"',
          height: 400,
          type: 'area',
          toolbar: {
              show: false
          },
          sparkline: {
              enabled: false,
          }
      },
      colors: ["#7B60E7", "#FF9BA3"],
      dataLabels: {
          enabled: false
      },
      stroke: {
          curve: 'smooth',
          width: 3,
      },
      yaxis: {
        show: true,
        labels: {
          show: true,
          minWidth: 19,
          maxWidth: 19,
          style: {
            colors: "#8A92A6",
          },
          offsetX: -5,
        },
      },
      legend: {
          show: false,
      },
      xaxis: {
          labels: {
              minHeight:22,
              maxHeight:22,
              show: true,
              style: {
                colors: "#8A92A6",
              },
          },
          lines: {
              show: false  //or just here to disable only x axis grids
          },
          categories: ["Jan", "Feb", "Mar", "Apr","May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
      },
      grid: {
          show: false,
      },
      fill: {
          type: 'gradient',
          gradient: {
              shade: 'dark',
              type: "vertical",
              shadeIntensity: 0,
              gradientToColors: undefined, // optional, if not defined - uses the shades of same color in series
              inverseColors: true,
              opacityFrom: .4,
              opacityTo: .1,
              stops: [0, 50, 80],
              colors: ["#7B60E7", "#FF9BA3"]
          }
      },
      tooltip: {
        enabled: true,
      },
  };

  var chart = new ApexCharts(document.querySelector("#admin-chart-04"), options);
  chart.render();
}
if (jQuery('#admin-chart-05').length) {
  const options = {
    series: [{
      name: 'Successful deals',
      data: [30, 80, 40, 50, 30, 70, 50, 40, 75, 30,]
    }],
    chart: {
      type: 'bar',
      height: 90,
      toolbar: {
          show:false
        },
        sparkline: {
          enabled: true,
      }
    },
    colors: [
      function({ value, seriesIndex, w }) {
        if (value < 50) {
          return '#7B60E7'
        } else {
          return '#B2A2F1'
        }
      }
    ],
    plotOptions: {
      bar: {
        columnWidth: '48%',
        borderRadius: 4,
      },
    },
    legend: {
      show: false
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    grid:{
      show: false,
    },
    xaxis: {
      labels: {
        show: false,
        minHeight:20,
        maxHeight:20
      }
    },
    yaxis: {
      show: false,
      title: {
        text: ''
      },
      labels: {
          minWidth: 19,
          maxWidth: 19,
          style: {
            colors: "#8A92A6",
          },
      }
    },
    fill: {
      opacity: 1
    },
    tooltip: {
      enabled: true,
    },
  };

  const chart = new ApexCharts(document.querySelector("#admin-chart-05"), options);
  chart.render();
}
if (jQuery('#admin-chart-06').length) {
  const options = {
    series: [{
      name: 'Successful deals',
      data: [30, 80, 40, 50, 30, 70, 50, 40, 42, 50,]
    }],
    chart: {
      type: 'bar',
      height: 90,
      toolbar: {
          show:false
        },
        sparkline: {
          enabled: true,
      }
    },
    colors: [
      function({ value, seriesIndex, w }) {
        if (value < 50) {
          return '#FEE5E7'
        } else {
          return '#FF9BA3'
        }
      }
    ],
    plotOptions: {
      bar: {
        columnWidth: '48%',
        borderRadius: 4,
      },
    },
    legend: {
      show: false
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    grid:{
      show: false,
    },
    xaxis: {
      labels: {
        show: false,
        minHeight:20,
        maxHeight:20
      }
    },
    yaxis: {
      show: false,
      title: {
        text: ''
      },
      labels: {
          minWidth: 19,
          maxWidth: 19,
          style: {
            colors: "#8A92A6",
          },
      }
    },
    fill: {
      opacity: 1
    },
    tooltip: {
      enabled: true,
    },
  };

  const chart = new ApexCharts(document.querySelector("#admin-chart-06"), options);
  chart.render();
}
if (jQuery('#admin-chart-07').length) {
  const options = {
    series: [55, 75],
    chart: {
    height: 260,
    type: 'radialBar',
  },
  colors: ["#7B60E7", "#FF9BA3"],
  plotOptions: {
    radialBar: {
      hollow: {
          margin: 10,
          size: "50%",
      },
      track: {
          margin: 10,
          strokeWidth: '50%',
      },
      dataLabels: {
          show: false,
      }
    }
  },
  labels: ['Apples', 'Oranges'],
  };
  if(ApexCharts !== undefined) {
    var chart = new ApexCharts(document.querySelector("#admin-chart-07"), options);
    chart.render();
  }
}
})(jQuery)
