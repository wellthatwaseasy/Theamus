function get_chart_data(pages) {
    var labels = [], data = [], info = [];

    for (key in pages) {
        labels.push(key);
        data.push(pages[key]);
    }

    info.push(labels);
    info.push(data);

    return info;
}

function show_count_chart(pages) {
    var info, chart_data, page_chart, largest, scale_override;

    info = get_chart_data(pages);
    chart_data = {
        labels: info[0],
        datasets: [{
                fillColor: "rgba(220,220,220,0.5)",
                strokeColor: "rgba(220,220,220,1)",
                data: info[1]
            }]
    };

    largest = Math.max.apply(Math, info[1]);
    scale_override = {
        scaleOverride: true,
        scaleSteps: 10,
        scaleStepWidth: Math.ceil(largest / 10),
        scaleStartValue: 0
    };

    if (typeof(Chart) !== "undefined") {
        page_chart = new Chart($("#page_canvas")[0].getContext("2d")).Bar(chart_data, scale_override);
        return page_chart;
    } else {
        setTimeout(function() {
            show_count_chart();
        }, 1000);
    }
}