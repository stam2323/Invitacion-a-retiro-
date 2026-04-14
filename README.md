# Invitación Retiro de Líderes - Grupo Angelus 👼✨

Este proyecto es una invitación digital interactiva diseñada para el retiro de líderes del Grupo Juvenil Angelus. Incluye un sistema de confirmación (RSVP) automatizado por correo electrónico y persistencia de datos.

## 🚀 Características
- **Diseño Premium**: Estética católica en Azul Mariano y Oro.
- **Identificación Persistente**: Guarda el nombre del usuario en el navegador (`localStorage`).
- **RSVP Automatizado**: Envía confirmaciones por correo electrónico usando PHPMailer y Gmail.
- **Motivos de Ausencia**: Captura la razón por la cual un líder no puede asistir.
- **Lista de Asistencia**: Genera automáticamente un archivo `confirmaciones.csv` con todas las respuestas.

## 🛠️ Instalación (Local)
1. Clona o descarga este repositorio en tu carpeta `htdocs` de XAMPP.
2. Asegúrate de tener habilitada la extensión de OpenSSL en tu PHP.
3. El sistema ya incluye PHPMailer en la carpeta `lib/`.

## ⚙️ Configuración
Para que el envío de correos funcione, edita el archivo `registro.php` con tus propias credenciales de Gmail:
```php
$mail->Username = 'tu-correo@gmail.com';
$mail->Password = 'tu-contraseña-de-aplicación';
```

## 📄 Créditos
Diseñado para el fortalecimiento del servicio en el **Grupo Juvenil Angelus**.
🕊️ "Sirvanse los unos a los otros por amor."
