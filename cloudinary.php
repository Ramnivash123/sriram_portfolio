<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - tksriram photography</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #121212;
      color: #ffffff;
    }

    nav {
      background-color: #1f1f1f;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 0 15px;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #bbb;
    }

    .brand {
      font-size: 20px;
      font-weight: bold;
    }

    .container {
      max-width: 600px;
      margin: 50px auto;
      background-color: #1f1f1f;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
    }

    h1 {
      font-size: 24px;
      margin-bottom: 10px;
      border-bottom: 1px solid #333;
      padding-bottom: 10px;
    }

    p {
      font-size: 16px;
      margin-bottom: 30px;
    }

    a.logout {
      color: #ff4c4c;
      text-decoration: none;
      font-weight: bold;
    }

    input[type="file"] {
      background: #2b2b2b;
      color: white;
      padding: 10px;
      border: 1px solid #444;
      border-radius: 5px;
      width: 100%;
      margin-top: 20px;
    }

    button {
      background-color: white;
      color: #121212;
      padding: 12px;
      border: none;
      border-radius: 5px;
      width: 100%;
      margin-top: 20px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #e0e0e0;
    }

    #preview {
      margin-top: 30px;
      text-align: center;
    }

    #preview img {
      max-width: 100%;
      border-radius: 10px;
      margin-top: 15px;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    .url-box {
      background-color: #2b2b2b;
      padding: 10px;
      border-radius: 5px;
      word-break: break-all;
      margin-top: 10px;
      color: #ccc;
      font-size: 14px;
    }

  </style>
</head>
<body>

  <nav>
    <div class="brand">tksriram photography</div>
    <div>
      <a href="index.php">Home</a>
      <a href="#">Portfolios</a>
      <a href="#">About</a>
    </div>
  </nav>

  <div class="container">
    <h1>Welcome to Dashboard</h1>
    <p>You are logged in as <strong>Admin</strong></p>
    <a href="logout.php" class="logout">Logout</a>

    <h1 style="margin-top: 40px;">Upload Photo to Cloudinary</h1>

    <input type="file" id="fileInput" accept="image/*" />
    <button onclick="uploadImage()">Upload Photo</button>

    <div id="preview"></div>
  </div>

  <script>
    async function uploadImage() {
      const file = document.getElementById("fileInput").files[0];
      if (!file) {
        alert("Please choose a file.");
        return;
      }

      const formData = new FormData();
      formData.append("file", file);
      formData.append("upload_preset", "#"); // Replace with your actual preset

      try {
        const response = await fetch("https://api.cloudinary.com/v1_1/#/image/upload", {
          method: "POST",
          body: formData
        });

        const data = await response.json();

        if (data.secure_url) {
          document.getElementById("preview").innerHTML = `
            <p><strong>Uploaded Image URL:</strong></p>
            <div class="url-box"><a href="${data.secure_url}" target="_blank">${data.secure_url}</a></div>
          `;
        } else {
          throw new Error(data.error.message);
        }
      } catch (error) {
        alert("Upload failed: " + error.message);
      }
    }
  </script>
</body>
</html>
