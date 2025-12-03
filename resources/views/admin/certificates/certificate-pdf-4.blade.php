<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Certificate</title>
<style>
  @page { size: A4; margin: 25mm; }
  body{ font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color:#222; }
  .container {
    border: 2px solid #ececec;
    padding: 28px;
    display:grid;
    grid-template-columns: 1fr 320px;
    gap:20px;
    align-items:center;
    height:100%;
  }
  .left { padding:10px; }
  .right { text-align:center; }
  .heading { font-size:20px; font-weight:700; color:#333; }
  .recipient { font-size:30px; font-weight:700; margin:14px 0; text-transform:capitalize; }
  .meta { color:#666; font-size:14px; }
  .logo { max-width:140px; margin-bottom:10px; }
  .stamp { border:1px dashed #ccc; padding:12px; display:inline-block; margin-top:16px; font-size:12px; color:#555; }
</style>
</head>
<body>
  <div class="container">
    <div class="left">
      <div class="heading">Certificate of Completion</div>
      <div class="recipient">{{ 'Learner_Name' }}</div>
      <div class="meta">has completed the <strong>{{ course_title }}</strong>.<br>Issued on {{ today()->format('d-M-Y') }}</div>
      <div style="margin-top:28px;">
        <div style="display:inline-block; width:200px;">
          <div style="border-top:1px solid #999; padding-top:8px; text-align:center;">Manager</div>
          <div style="text-align:center; font-size:12px; color:#777;">Training & Development</div>
        </div>
      </div>
    </div>
    {{-- <div class="right">
      <img src="{{ asset('lms-img/lmskey-logo.png') }}" alt="logo" class="logo">
      <div class="stamp">Certificate ID: {{ certificate_id }}</div>
    </div> --}}
  </div>
</body>
</html>
