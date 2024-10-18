<script src="{{ url('assets/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ url('assets/js/popper.min.js') }}"></script>
<script src="{{ url('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ url('assets/js/main.js') }}"></script>
<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', () => {
  const togglers = document.querySelectorAll('[data-toggle]');
  
    togglers.forEach((btn) => {
      btn.addEventListener('click', (e) => {
         const selector = e.currentTarget.dataset.toggle
         const block = document.querySelector(`${selector}`);
        if (e.currentTarget.classList.contains('active')) {
          block.style.maxHeight = '';
        } else {
          block.style.maxHeight = block.scrollHeight + 'px';
        }
          
         e.currentTarget.classList.toggle('active')
      })
    })
	})  
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('.password-toggle-icon');
        const passwordField = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the eye icon class
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });

    $(document).ready(function() {
    // Check if error message exists
    if ($('.password-error').length > 0) {
        // If an error is present, adjust the eye icon position
        $('.password-toggle-icon').css({
            'top': '15%', // Adjust icon position to accommodate error message
            'transform': 'none' // Remove translate to prevent centering
        });
    } else {
        // If no error, keep the icon centered
        $('.password-toggle-icon').css({
            'top': '50%', // Vertically center the icon
            'transform': 'translateY(-50%)' // Center transform
        });
    }
});
</script>
</body>
</html>