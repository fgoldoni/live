<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" xmlns="http://www.w3.org/1999/xhtml"
      xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>{{ __('Your magic sign-in link') }}</title>
</head>
<body style="margin:0;padding:0;background:#f5f6f8;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;word-spacing:normal;">

<!-- Preheader -->
<div style="display:none!important;max-height:0;max-width:0;overflow:hidden;opacity:0;color:transparent;mso-hide:all;">
    {{ __('Click the button below to sign in instantly.') }}
</div>
<div style="display:none!important;max-height:0;max-width:0;overflow:hidden;opacity:0;color:transparent;mso-hide:all;white-space:nowrap;font-size:1px;line-height:1px;">
    &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
</div>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background:#f5f6f8;">
    <tr>
        <td align="center" style="padding:20px 12px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                   style="width:100%;max-width:720px;background:#ffffff;border-radius:14px;overflow:hidden;
                    box-shadow:0 6px 22px rgba(16,24,40,0.08);border:1px solid #eef0f4;">

                <!-- Accent -->
                <tr><td style="height:4px;background:linear-gradient(90deg,#d4af37,#f5d778,#d4af37);font-size:0;line-height:0;">&nbsp;</td></tr>

                <!-- Header -->
                <tr>
                    <td align="center" style="padding:24px 16px;background:#ffffff;">
                        @if(!empty($logoUrl))
                            <img src="{{ $logoUrl }}" width="150" alt="Logo"
                                 style="display:block;border:0;outline:none;text-decoration:none;height:auto;max-width:180px;margin:0 auto 6px;">
                        @endif
                        <div style="font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:18px;color:#667085;margin:0;">
                            {{ config('app.name') }}
                        </div>
                    </td>
                </tr>

                <!-- Title -->
                <tr>
                    <td align="center" style="padding:8px 16px 0;background:#ffffff;">
                        <h1 style="font-family:Arial,Helvetica,sans-serif;font-size:22px;line-height:28px;color:#101828;margin:0;font-weight:800;">
                            {{ __('Sign in with your magic link') }}
                        </h1>
                        <p style="font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:22px;color:#475467;margin:10px 0 0;">
                            {{ __('Click the button below to finish signing in. This link may be used once and will expire soon.') }}
                        </p>
                    </td>
                </tr>

                <!-- CTA -->
                <tr>
                    <td align="center" style="padding:16px 16px 8px;background:#ffffff;">
                        <!--[if mso]>
                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" href="{{ $url }}"
                          style="height:46px;v-text-anchor:middle;width:320px;" arcsize="12%" strokecolor="#b88a0a" strokeweight="1px" fillcolor="#d4af37">
                          <w:anchorlock/>
                          <center style="color:#111111;font-family:Arial, Helvetica, sans-serif;font-size:16px;font-weight:800;">
                            {{ __('Sign in now') }}
                        </center>
                      </v:roundrect>
<![endif]-->
                        <!--[if !mso]><!-- -->
                        <a href="{{ $url }}"
                           style="display:inline-block;background:#d4af37;border:1px solid #b88a0a;border-radius:12px;
                      padding:13px 22px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;
                      font-weight:800;text-decoration:none;color:#111111;box-shadow:0 3px 12px rgba(212,175,55,0.25),
                      inset 0 0 0 1px rgba(0,0,0,0.06);">
                            <span style="color:#111111;text-decoration:none;">{{ __('Sign in now') }}</span>
                        </a>
                        <!--<![endif]-->
                    </td>
                </tr>

                <!-- Fallback link -->
                <tr>
                    <td align="center" style="padding:8px 16px 16px;background:#ffffff;">
                        <p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:18px;color:#667085;margin:0;">
                            {{ __('If the button doesn’t work, copy and paste this link into your browser:') }}
                        </p>
                        <p style="font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:18px;color:#175cd3;margin:6px 0 0;word-break:break-all;">
                            <a href="{{ $url }}" style="color:#175cd3;text-decoration:underline;">{{ $url }}</a>
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center" style="padding:14px 16px 20px;background:#ffffff;border-top:1px solid #eef0f4;">
                        <div style="font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:18px;color:#98a2b3;margin:0;">
                            © {{ date('Y') }} {{ config('app.name') }} — {{ __('All rights reserved.') }}
                        </div>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
