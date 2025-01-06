<!DOCTYPE html>
<html>
<head>
    <title>Email Verification Code</title>
</head>
<body>

    <table border="0" cellpadding="0" cellspacing="0" class="m_-173531503285008053wrapper" style="width:640px;border-collapse:separate;border-spacing:0;margin:0 auto">
        <tbody>
        <tr>
        <td class="m_-173531503285008053wrapper-cell" style="font-family:&quot;Helvetica Neue&quot;,Helvetica,Arial,sans-serif;border-radius:3px;overflow:hidden;padding:18px 25px;border:1px solid #ededed" align="left" bgcolor="#fff">
        <table border="0" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:separate;border-spacing:0">
        <tbody>
        <tr><td><div style="color:#1f1f1f;line-height:1.25em;max-width:400px;margin:0 auto" align="center">
        <h3>
        Help us protect your account
        </h3>
        <p style="font-size:0.9em">
        Before you sign in, we need to verify your identity. Enter the following code on the sign-in page.
        </p>
        <div style="width:207px;height:53px;background-color:#f0f0f0;line-height:53px;font-weight:700;font-size:1.5em;color:#303030;margin:26px 0">
            {{ $code }}
        </div>
        <p style="font-size:0.75em">
            Your verification code expires after 5 minutes.
        </p>
        </div>
        
        </td></tr></tbody>
        </table>
        </td>
        </tr>
        </tbody>
        </table>
</body>
</html>