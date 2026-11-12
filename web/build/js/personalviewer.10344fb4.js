"use strict";


//alert('ok');

function inter_init() {

    document.getElementById('searchform-id').focus();
   /* $("#searchform-id").change(function() {
        console.log($(this).val());
    });*/
    $("#searchform-id").on("change paste input", function() {
     //   console.log($(this).val());
        const $yiiform = $('#search-form');
        let form = document.getElementById('search-form');
      //  console.log($yiiform.attr('method'),$yiiform.attr('action'),$yiiform.serializeArray());
        $.ajax({
                type: $yiiform.attr('method'),
                url: $yiiform.attr('action'),
                data: $yiiform.serializeArray(),
            success: function(response) {
          //      console.log(response);
                if (response !== null && typeof response === 'object') {
                    let helps = $('#searchform-id ~ div.help-block');
                    $.each(helps, function(index, value) {
                        value.innerHTML = '';
                    });
                    $('#searchform-id ~ div.help-block')[0].innerHTML = '';
                    $('#searchform-id').parent('.field-searchform-id').removeClass('has-error');
                    if(response['searchform-id']){

                        $('#searchform-id ~ div.help-block')[0].innerHTML = response['searchform-id'][0];
                        $('#searchform-id').parent('.field-searchform-id').addClass('has-error');
             //           $('#searchform-id').parent('.field-searchform-id').addClass('has-success');
                    }
       //             console.log(response.searchform-id);
                   /* $.each(data.attributes, function () {
                        if (!this.enableAjaxValidation || this.cancelled) {
                            delete msgs[this.id];
                        }
                    });
                    updateInputs($form, $.extend(messages, msgs), submitting);*/
                } else  {
               /*     console.log(response.length);
                    let helps = $('#searchform-id ~ div.help-block');
                    $.each(helps, function(index, value) {
                        value.innerHTML = '';
                    });*/

 //                   updateInputs($form, messages, submitting);
                }
            },
            error: function(response) {
                console.log(response);

            }


            }
        )


    });


    /*let input = document.getElementById('searchform-id');
    input.addEventListener('onkeydown', function () {
        let $yiiform = $(this);
        console.log(input.value);
        // отправляем данные на сервер
        $.ajax({
                type: $yiiform.attr('method'),
                url: $yiiform.attr('action'),
                data: $yiiform.serializeArray()
            }
        )
            .done(function(data) {
                if(data.success) {
                    console.log(data);
                    //
                } else {
                    //
                }
            })
            .fail(function () {
                //
            })

        return false; // cancel
    });
*/


}


window.onload=inter_init;
