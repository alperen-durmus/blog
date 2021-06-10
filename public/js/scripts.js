const host = "https://localhost:8000";
/** reply a comment **/

$(".reply").click(function(e) {
    e.preventDefault();
    const urlParams = new URLSearchParams(window.location.search);
    const id = this.dataset.parentComment;

    $.ajax({
        url: `${host}/comment/reply/${id}`,
        method: "post",
        header: {
            "Access-Control-Allow-Origin": "*"
        },
        success: async function (response) {
            const action = await $("#commentForm").attr("action");

            await $("[name=content]").val(`@${response.owner} `).focus();
            await $("#commentForm").attr("action", `${action}?comment=${response.parent_id}`);
        }
    })

});

// $(function (){
//     var ctx = document.getElementById('myChart');
//     console.log(ctx)
//     var myChart = new Chart(ctx, {
//         type: "doughnut",
//         data: {
//             labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
//             datasets: [{
//                 label: '# of Votes',
//                 data: [12, 19, 3, 5, 2, 3],
//                 backgroundColor: [
//                     'rgba(255, 99, 132, 0.2)',
//                     'rgba(54, 162, 235, 0.2)',
//                     'rgba(255, 206, 86, 0.2)',
//                     'rgba(75, 192, 192, 0.2)',
//                     'rgba(153, 102, 255, 0.2)',
//                     'rgba(255, 159, 64, 0.2)'
//                 ],
//
//                 borderWidth: 1
//             }],
//             options: {
//                 scales: {
//                     y: {
//                         beginAtZero: true
//                     }
//                 }
//             }
//         }
//     })
// })

$(function () {
    renderPieChart("category")
    renderPieChart("tag")

    function renderPieChart(type) {
            $.getJSON(`${host}/admin/get-dashboard-data`,  async function(response) {
                let series = await response[type].map(d => d.count)
                let labels = await response[type].map(d => d.name)
                let  options = {
                    series: series,
                    labels: labels,
                    noData: {
                        text: "no data yet",
                    },
                    chart: {
                        type: 'pie',
                    },
                }

            let chart = new ApexCharts(document.querySelector(`#${type}Chart`), options);
            chart.render();
        });
    }


    $.getJSON(`${host}/admin/get-dashboard-data`,  async function(response) {
        console.log(response.user)
        let chart = new ApexCharts(document.querySelector(`#userChart`), {
            series: [{
                data: response.user,
            }],
            noData: {
                text: "no data yet",
            },
            chart: {
                type: 'bar',
            },
        });
        chart.render();
    })
})
