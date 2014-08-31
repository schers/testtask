/**
 * Created by Sergey on 01.09.14.
 */
$(function(){
    $('#open_generate').on('click', function(e){
        e.preventDefault();
        $('#generate_form_container').toggle('slow');
    });

    $('#generate_form').on('submit', function(e){
        e.preventDefault();
        $(this).ajaxSubmit({
            success: function(data){
                if (data.success){
                    location.reload();
                } else if (data.error){
                    alert(data.message);
                } else {
                    alert('Ошибка генерации данных. Попробуйте проделать операцию позже.')
                }
            },
            dataType: 'json'
        });
    });
})
