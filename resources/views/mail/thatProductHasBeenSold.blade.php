<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ваш товар был приобретен</title>
  <style>
    /* Стили для адаптивности */
    body {
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      font-family: Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      width: 100% !important;
    }
    .wrapper {
      width: 100%;
      background-color: #f4f4f4;
      padding: 40px 0;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .header {
      background-color: #2c3e50;
      padding: 30px;
      text-align: center;
    }
    .content {
      padding: 40px;
      color: #333333;
      font-size: 16px;
      line-height: 1.6;
    }
    .button-container {
      text-align: center;
      margin: 30px 0;
    }
    .button {
      background-color: #3498db;
      color: #ffffff;
      text-decoration: none;
      padding: 15px 30px;
      border-radius: 5px;
      font-weight: bold;
      display: inline-block;
    }
    .footer {
      background-color: #f4f4f4;
      padding: 20px;
      text-align: center;
      font-size: 12px;
      color: #888888;
    }
  </style>
</head>
<body style="margin: 0; padding: 0;">
  <div class="wrapper">
    <table class="container" width="100%" cellpadding="0" cellspacing="0" border="0"> 
      <!-- Основной текст -->
      <tr>
        <td class="content">
          <p>Здравствуйте!</p>
          <p> Ваш Арт {{ $product->name }} был продан.</p>
           
          <p> Покупатель {{$buyer->email}}.</p>
          <p>С уважением,<br>pet-project-laravel</p>
        </td>
      </tr>
    </table>
  </div>
</body>
</html>