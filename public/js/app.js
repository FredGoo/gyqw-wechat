$(function(){
  // my
  $('#select-channel').val(config.channel);

  $('#select-channel').on('change', function(){
    var url = $(this).data('url');
    var channel = $(this).val();

    location.href = url+'/'+channel;
  });
});
