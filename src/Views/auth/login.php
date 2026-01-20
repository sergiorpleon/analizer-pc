<?php
$title = 'Iniciar Sesión';
ob_start();
?>

<div
    style="max-width: 400px; margin: 60px auto; padding: 40px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
    <h1 style="text-align: center; color: #667eea; margin-bottom: 30px;">🔐 Iniciar Sesión</h1>

    <?php if (isset($error)): ?>
        <div
            style="padding: 15px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px;">
            ❌
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/login" style="display: flex; flex-direction: column; gap: 20px;">
        <div>
            <label for="username" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                Usuario
            </label>
            <input type="text" id="username" name="username" required autocomplete="username"
                style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#ddd'">
        </div>

        <div>
            <label for="password" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">
                Contraseña
            </label>
            <input type="password" id="password" name="password" required autocomplete="current-password"
                style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#ddd'">
        </div>

        <button type="submit"
            style="padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-size: 1.1em; font-weight: 600; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.4)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            Iniciar Sesión
        </button>
    </form>

    <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; text-align: center;">
        <p style="margin: 0; color: #666; font-size: 0.9em;">
            <strong>Credenciales por defecto:</strong><br>
            Usuario: <code style="background: white; padding: 2px 8px; border-radius: 4px;">admin</code><br>
            Contraseña: <code style="background: white; padding: 2px 8px; border-radius: 4px;">admin123</code>
        </p>
    </div>

    <div style="margin-top: 20px; text-align: center;">
        <a href="/" style="color: #667eea; text-decoration: none;">
            ← Volver al inicio
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title; ?>
    </title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
    </style>
</head>

<body>
    <?php echo $content; ?>
</body>

</html>