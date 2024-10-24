<html>
<link href='https://fonts.googleapis.com/css?family=Public Sans' rel='stylesheet'>
<style>
body {
    font-family: 'Public Sans';
}
</style>
<body>
<center>		
<h1>Email Verification Mail</h1>
Welcome to SerpSupport. Please verify your email with below link: 
<a href="{{ route('account/verify', $token) }}">Verify Email</a>
</center>
</body>
</html>