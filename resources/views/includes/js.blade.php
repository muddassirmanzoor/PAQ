<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#submitTo').change(function() {
            var departmentId = $(this).val();
            if(departmentId) {
                $.ajax({
                    url: '/get-users-by-department/' + departmentId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#pot').empty();
                        $('#pot').append('<option value="">Select Point of Contact</option>');
                        $.each(data, function(key, user) {
                            $('#pot').append('<option value="'+ user.id +'">'+ user.full_name +'</option>');
                        });
                    }
                });
            } else {
                $('#pot').empty();
                $('#pot').append('<option value="">Select Point of Contact</option>');
            }
        });
    });
</script>
