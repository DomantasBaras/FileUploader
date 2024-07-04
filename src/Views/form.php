<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
            background-color: #7b2cbf;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .formbold-main-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        .formbold-form-wrapper {
            max-width: 550px;
            width: 100%;
            background-color: #f7fff7;
            padding: 30px 60px;
            border-radius: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 10px 20px, rgba(0, 0, 0, 0.28) 0px 6px 6px;
        }
        .formbold-form-label-2 {
            font-weight: 600;
            font-size: 30px;
            margin-bottom: 20px;
            text-align: center;
            color: #07074d;
        }
        .formbold-file-input {
            position: relative;
            border: 2px dashed #7b2cbf;
            border-radius: 40px;
            margin: 10px 0 15px;
            padding: 30px 50px;
            width: 350px;
            text-align: center;
            min-height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .formbold-file-input label {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .formbold-file-input input {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .formbold-browse {
            font-weight: bolder;
            font-size: 20px;
            color: #7b2cbf;
        }
        .formbold-btn {
            font-family: 'Montserrat';
            text-align: center;
            font-size: 20px;
            border-radius: 20px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error {
            color: red;
            margin-top: 20px;
            text-align: center;
            width: 100%;
            position: relative;
        }
        #error-container {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 20px; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #e2e8f0;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
        }
        tr:nth-child(even) {
            background-color: #f7fafc;
        }
        @media (max-width: 600px) {
            .formbold-form-wrapper {
                padding: 16px;
            }
            table, th, td {
                font-size: 0.9rem;
            }
        }
        .formbold-upload-btn {
            width: 300px;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            margin: 20px;
            height: 55px;
            text-align: center;
            border: none;
            background-size: 300% 100%;
            border-radius: 50px;
            transition: all .4s ease-in-out;
        }
        .formbold-upload-btn:hover {
            background-position: 100% 0;
            transition: all .4s ease-in-out;
        }
        .formbold-upload-btn:focus {
            outline: none;
        }
        .formbold-upload-btn.bn20 {
            background-image: linear-gradient(
                to right,
                #667eea,
                #764ba2,
                #6b8dd6,
                #8e37d7
            );
            box-shadow: 0 4px 15px 0 rgba(116, 79, 168, 0.75);
        }
        .formbold-pt-3 {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        p {
            text-align: center;
            width: 100%;
            font-size: 20px;
        }
    </style>
</head>
<body>
  <div class="formbold-main-wrapper">
    <div class="formbold-form-wrapper">
      <header class="formbold-form-label-2">Upload File</header>
      <form id="uploadForm" method="POST" enctype="multipart/form-data">
        <div class="formbold-file-input">
          <label for="file">
            <span class="formbold-browse" id="fileLabel">Browse</span>
            <input type="file" name="file" id="file">
          </label>
        </div>
        <div id="error-container">
          <?php
          if (isset($error)) {
            if (is_array($error)) {
              echo '<p class="error">' . implode('<br>', array_map('htmlspecialchars', $error)) . '</p>';
            } else {
              echo '<p class="error">' . htmlspecialchars($error) . '</p>';
            }
          }
          ?>
          <p id="fileError" class="error" style="display: none;"></p>
        </div>
        <div class="formbold-pt-3">
          <button type="submit" class="formbold-upload-btn bn20">Upload</button>
        </div>
        <p>Allowed formats: csv, json, xml</p>
      </form>
      <?php if (isset($data) && !empty($data)): ?>
        <div class="formbold-form-label-2">Uploaded Data</div>
        <table>
          <thead>
            <tr>
              <?php foreach (array_keys($data[0]) as $header): ?>
                <th><?php echo htmlspecialchars($header); ?></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $row): ?>
              <tr>
                <?php foreach ($row as $cell): ?>
                  <td><?php echo htmlspecialchars($cell); ?></td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php unset($data); // Clear the data after displaying it ?>
      <?php endif; ?>
    </div>
  </div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById("file");
        const fileLabel = document.getElementById("fileLabel");
        const fileError = document.getElementById("fileError");
        const uploadForm = document.getElementById("uploadForm");

        fileInput.addEventListener("change", function() {
            const file = fileInput.files[0];
            if (file) {
                fileError.style.display = "none"; // Hide any previous errors
                fileLabel.textContent = file.name;
            } else {
                fileLabel.textContent = "Browse";
            }
        });

        uploadForm.addEventListener("submit", function(event) {
            if (fileInput.files.length === 0) {
                fileError.textContent = "Please select a file to upload.";
                fileError.style.display = "block";
                event.preventDefault(); // Prevent default form submission
            }
        });
    });
</script>
</body>
</html>
