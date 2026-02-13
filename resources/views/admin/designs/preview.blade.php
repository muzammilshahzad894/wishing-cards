<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $design->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f0ebe3; padding: 1rem; margin: 0; }</style>
</head>
<body>
    @include('cards.templates.' . $design->template_key, [
        'cardImage' => $cardImage,
        'cardName' => $cardName,
        'greetingText' => $greetingText,
    ])
</body>
</html>
