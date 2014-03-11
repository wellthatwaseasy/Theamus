var ajax = new function() {
    this.fail = false;
    this.has_file = false;
    this.hideable = false;

    this.run = function(info) {
        var form_data, url;

        form_data = this.get_form_data(info);
        form_data = this.get_extra_fields(info, form_data);
        form_data = this.add_ajax_type(info, form_data);
        form_data = this.add_file_object(info, form_data);

        url = this.sanitize_url(info);

        if (this.fail === false) {
            $.ajax({
                type: 'POST',
                url: url,
                data: form_data,
                processData: this.processData,
                contentType: this.contentType,
                xhr: function() {
                    var xhr = new XMLHttpRequest();


                    if (window.FormData !== undefined) {
                        xhr.upload.addEventListener("progress", function(event) {
                            ajax.show_upload(event, info);
                        }, false);
                    }

                    return xhr;
                },
                success: function (return_data) {
                    ajax.hide_upload(info);
                    ajax.show_results(info, return_data);
                    ajax.run_after(info);
                    ajax.run_return_functions();
                    add_extras();
                },
                error: function (xhr, status, error) {
                    console.log("AJAX Error: " + error);
                }
            });
        } else {
            console.log("AJAX Setup Error: " + this.fail);
        }

        return false;
    };

    this.get_form_data = function(info) {
        var form, form_elements, element_values, form_data;

        if ("form" in info) {
            form = $('#' + info.form);
            if (form.length > 0) {
                form_elements = this.get_form_elements(info.form);
                element_values = this.get_element_values(form_elements);
                form_data = this.make_form_data(element_values);
            } else {
                form_data = this.form_data();
            }
        } else {
            form_data = this.form_data();
        }

        return form_data;
    };

    this.form_data = function() {
        if(window.FormData === undefined) {
            this.processData = true;
            this.contentType = 'application/x-www-form-urlencoded';
            return {};
        } else {
            var formdata = new FormData();
            this.processData = false;
            this.contentType = false;
            return formdata;
        }
    };

    this.form_data_append = function(parent, key, value) {
        if(window.FormData === undefined) {
            parent[key] = value;
        } else {
            parent.append(key, value);
        }
        return parent;
    };

    this.get_form_elements = function(form_id) {
        var elements = $("#" + form_id + " :input");
        return elements;
    };

    this.reset_form_elements = function(form_id, exceptions) {
        var elements = this.get_form_elements(form_id);
        exceptions = exceptions !== undefined ? exceptions : new Array();

        for (var i = 0; i < elements.length; i++) {
            if (exceptions.indexOf(elements[i].name) === -1) {
                if (elements[i].type !== "button" && elements[i].type !== "submit") {
                    elements[i].value = "";
                }
            }
        }
    };

    this.get_id_elements = function(element_ids) {
        var elements = new Array();
        for (var i = 0; i < element_ids.length; i++) {
            elements.push($("#" + element_ids[i])[0]);
        }

        return elements;
    };

    this.get_element_values = function(elements) {
        var elements_values, element, value;
        elements_values = new Array();

        for (var i = 0; i < elements.length; i++) {
            element = elements[i];

            if (element.type === "checkbox") {
                value = element.checked === true ? "true" : "false";

            } else if (element.type === "file" && window.FormData !== undefined) {
                if (element.files.length > 0) {
                    if (element.files.length >= 1) {
                        value = element.files[0];
                        this.has_file = true;
                    } else {
                        value = "false";
                        this.has_file = false;
                    }
                }

            } else if (element.type === "radio") {
                var radio = $("[name='" + element.name + "']");
                for ( var r = 0; r < radio.length; r++) {
                    if (radio[r].checked) {
                        value = radio[r].value;
                        break;
                    }
                }

            } else if (element.tagName.toLowerCase() === "select") {
                if (element.getAttribute("multiple") === "multiple") {
                    var options, option_values;
                    options = element.options;
                    option_values = new Array;

                    for (var o = 0; o < options.length; o++) {
                        if (options[o].selected === true) {
                            option_values.push(options[o].value);
                        }
                    }
                    value = option_values.join(",");
                } else {
                    value = element.value;
                }

            } else if (element.tagName.toLowerCase() === "div") {
                value = element.innerHTML;
            } else {
                value = element.value;
            }

            if (element.name === undefined) {
                elements_values[element.id] = value;
            } else {
                elements_values[element.name] = value;
            }
        }

        return elements_values;
    };

    this.make_form_data = function(elements, form_data) {
        if (form_data === undefined || form_data === null) {
            form_data = this.form_data();
        }
        var value;

        for (key in elements) {
            if (typeof elements[key] === 'object') {
                value = elements[key];
            } else {
                value = encodeURIComponent(elements[key]);
            }
            form_data = this.form_data_append(form_data, key, value);
        }

        return form_data;
    };

    this.get_extra_fields = function(info, form_data) {
        var fields, elements, values;

        if ("extra_fields" in info) {
            fields = info.extra_fields;

            if ($.isArray(fields) === false) {
                fields = new Array();
                fields.push(info.extra_fields);
            }

            elements = this.get_id_elements(fields);
            values = this.get_element_values(elements);
            form_data = this.make_form_data(values, form_data);

            return form_data;
        } else {
            return form_data;
        }
    };

    this.sanitize_url = function(info) {
        var trailing, url;

        if ("url" in info) {
            trailing = info.url.slice(-1) !== "/" ? "" : "";
            url = info.url + trailing;

            return url;
        } else {
            this.fail = "There is no URL to go to.";
        }
    };

    this.add_ajax_type = function(info, form_data) {
        var type = "type" in info ? info.type : "script";
        form_data = this.form_data_append(form_data, "ajax", type);
        return form_data;
    };

    this.get_result_area = function(info) {
        if ("result" in info) {
            if ($("#" + info.result).length > 0) {
                return $("#" + info.result);
            } else {
                this.fail = "The AJAX result div provided doesn't exist.";
            }
        } else {
            this.fail = "There is nowhere to put the AJAX results.";
        }
    };

    this.show_upload = function(event, info) {
        var percent_completed, show_number;
        var percent_growbar = "percent_growbar";
        var percent_number = "percent_number";
        var stop = 5;
        var show = true;

        if ("upload" in info) {
            percent_growbar = "growbar" in info.upload ? info.upload.growbar : percent_growbar;
            percent_number = "percentage" in info.upload ? info.upload.percentage : percent_number;
            stop = "stop" in info.upload ? info.upload.stop : stop;
            show = "show" in info.upload ? info.upload.show : show;
        }

        if ($("#" + percent_growbar).length < 1) show = false;
        if ($("#" + percent_number).length < 1) show_number = false;

        if (event.lengthComputable) {
            if ($("#" + percent_growbar).length > 0) {
                if ($("#" + percent_growbar)[0].style.display !== "block") {
                    $("#" + percent_growbar).show();
                }
            }

            percent_completed = Math.floor((event.loaded / event.total) * 100);
            if (show === true && this.has_file !== false) {
                $("#" + percent_growbar).show();
                $("#" + percent_growbar)[0].style.width = percent_completed * stop + "px";
                if (show_number !== false) $("#" + percent_number).html(percent_completed + "%");
                this.hideable = true;
                this.stop = stop;
            }
        }
    };

    this.hide_upload = function(info) {
        if (this.hideable === true) {
            if ("upload" in info) {
                var hide = "hide" in info.upload ? info.upload.hide : true;
                var hide_time = "hide_time" in info.upload ? info.upload.hide_time : 3;
                hide_time = hide_time * 1000;
                var percent_growbar = "growbar" in info.upload ? info.upload.growbar : "percent_growbar";
                var percent_number = "percentage" in info.upload ? info.upload.percentage : "percent_number";

                $("#" + percent_growbar)[0].style.width = (100 * this.stop) + "px";
                $("#" + percent_number).html("100%");

                if (hide === true) {
                    setTimeout(function() {
                        $("#" + percent_growbar).hide();
                    }, hide_time);
                }
            }
        }
    };

    this.run_after = function(info) {
        var do_function, arguments;
 
        if ("after" in info) {
            if (typeof info.after === "function") info.after();
            else if ("do_function" in info.after) {
                do_function = info.after.do_function;
                if (typeof do_function === 'string') {
                    arguments = "arguments" in info.after ? info.after.arguments : "";
                    window[do_function](arguments);
                } else {
                    for (var i=0; i< do_function.length; i++) {
                        arguments = "arguments" in info.after ? info.after.arguments[i] : "";
                        window[do_function[i]](arguments);
                    }
                }
            }
        }
    };

    this.run_return_functions = function() {
        var do_function, arguments, info;
        var after_elements = $("[name='run_after']");

        if (after_elements.length > 0) {
            for (var i = 0; i < after_elements.length; i++) {
                do_function = after_elements[i].getAttribute("function");
                arguments = after_elements[i].getAttribute("arguments");
                if (arguments !== "") arguments = $.parseJSON(arguments);

                window[do_function](arguments);
                after_elements[i].remove();
            }
        }
    };

    this.show_results = function(info, data) {
        var result = this.get_result_area(info);
        if (result !== undefined) {
            result.show();
            result.html(data);

            if ("hide_result" in info) {
                setTimeout(function() {
                    result.hide();
                }, (info.hide_result * 1000));
            }
        } else {
            console.log(this.fail);
        }
    };

    this.add_file_object = function(info, form_data) {
        if ("file_object" in info) {
            form_data = this.form_data_append(form_data, "file", info.file_object);
            this.has_file = true;
        }

        return form_data;
    };
};