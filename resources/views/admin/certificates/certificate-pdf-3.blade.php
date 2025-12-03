<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Certificate</title>
<style>
  @page { size: A4 landscape; margin: 18mm; }
  body {
    font-family: "Helvetica Neue", Arial, sans-serif;
    margin:0; padding:0;
  }
  .wrap {
    height:100%;
    background: linear-gradient(135deg,#1f8ef1 0%, #6dd5ed 100%);
    padding: 30px;
    color: #fff;
    box-sizing:border-box;
    border-radius: 6px;
  }
  .card {
    background: rgba(255,255,255,0.95);
    color: #222;
    border-radius: 8px;
    padding: 30px;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
  }
  .header { display:flex; justify-content:space-between; align-items:center; }
  .logo{max-height:60px;}
  .badge { font-weight:700; color:#1f8ef1; }
  .headline { text-align:center; margin:30px 0; }
  .headline h1{ margin:0; font-size:32px; letter-spacing:1px; }
  .headline p{ margin:6px 0 0; color:#666; }
  .name { text-align:center; font-size:34px; font-weight:700; margin-top:20px; text-transform:capitalize; }
  .details { text-align:center; margin-top:16px; color:#444; font-size:16px; }
  .footer { display:flex; justify-content:space-between; margin-top:50px; align-items:center;}
  .sig { text-align:center; }
  .qr { text-align:right; }
  .small { font-size:12px; color:#888; }
</style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="header">
        <img src="{{ asset('lms-img/lmskey-logo.png') }}" alt="logo" class="logo">
        <div class="badge">Certificate</div>
      </div>

      <div class="headline">
        <h1>Certificate of Achievement</h1>
        <p>Presented to</p>
      </div>

      <div class="name">{{ 'Learner_Name' }}</div>

      <div class="details">
        For successfully completing <strong>{{ "Course_Title" }}</strong><br>
        Date: {{ today()->format('d-M-Y') }}
      </div>

      <div class="footer">
        <div class="sig">
          <div style="margin-bottom:8px; border-top:1px solid #ddd; width:160px;"></div>
          <div class="small">Manager<br>Training & Development</div>
        </div>

        {{-- <div class="qr">
          <!-- optionally place QR image -->
          <img src="{{ qr_image_url }}" alt="QR" style="height:80px;">
          <div class="small">Certificate ID: {{ certificate_id }}</div>
        </div>
      </div> --}}
    </div>
  </div>
</body>
</html>
