<html>
  <head></head>
  <body>
    <h1>Invitation to colaborate in asme</h1>
    <p>Hello {{ $invite->name }}</p>
    <p>Email: {{ $invite->email }}</p>
    <p>{{ $invite->message }}</p>
    <p>This is invitation mail to be a contributor for asme project.</p>
    <a href="{{ route('accept-invite', $invite->token) }}">Click here</a> to activate!
  </body>
</html>