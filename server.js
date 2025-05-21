require('dotenv').config();
const express = require('express');
const nodemailer = require('nodemailer');
const path = require('path');

const app = express();
const PORT = 3000;

let generatedCode = null;
let isVerified = false;

app.use(express.static('public'));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

const transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: process.env.EMAIL_FROM,
    pass: process.env.EMAIL_PASS,
  }
});

// Rota para enviar código
app.post('/send-code', async (req, res) => {
  const { email, myname } = req.body;
  generatedCode = Math.floor(100000 + Math.random() * 900000).toString();
  console.log(generatedCode)
  try {
    await transporter.sendMail({
      from: process.env.EMAIL_FROM,
      to: email,
      subject: 'Boa noite '+ myname +' Seu código de verificação chegou...!',
      text: myname + ` Seu código é: ${generatedCode}`,
    });

    res.json({ success: true, message: 'Código enviado para seu e-mail!' });
  } catch (err) {
    console.error(err);
    res.status(500).json({ success: false, message: 'Erro ao enviar o e-mail.' });
  }
});

// Rota para validar código
app.post('/validate-code', (req, res) => {
  const { code } = req.body;
  if (code === generatedCode) {
    generatedCode = null
    res.json({ success: true });
  } else {
    res.json({ success: false });
  }
});

// Rota protegida
app.get('/form', (req, res) => {
  if (isVerified) {
    res.sendFile(path.join(__dirname, 'public/form.html'));
  } else {
    res.redirect('/');
  }
});

app.listen(PORT, () => {
  console.log(`Servidor rodando: http://localhost:${PORT}`);
});
