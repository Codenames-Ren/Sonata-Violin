<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pembayaran Ditolak - Sonata Violin</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, Helvetica, sans-serif !important;}
    </style>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <!-- Main Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); padding: 40px 20px;">
        <tr>
            <td align="center">
                
                <!-- Email Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background: #1e293b; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                    
                    <!-- Header with Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 32px; font-weight: 900; letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                🎻 SONATA VIOLIN
                            </h1>
                            <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.9); font-size: 14px; font-weight: 500; letter-spacing: 0.5px;">
                                Professional Violin Course
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Rejected Badge -->
                    <tr>
                        <td style="padding: 30px 30px 20px 30px; text-align: center;">
                            <div style="display: inline-block; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); padding: 12px 32px; border-radius: 50px; box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);">
                                <span style="color: #ffffff; font-size: 14px; font-weight: 700; letter-spacing: 0.5px;">
                                    ✕ PEMBAYARAN DITOLAK
                                </span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Rejected Icon -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px; text-align: center;">
                            <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 50%; box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3); position: relative;">
                                <span style="color: #ffffff; font-size: 48px; line-height: 80px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">✕</span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Greeting -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <h2 style="margin: 0 0 16px 0; color: #ffffff; font-size: 24px; font-weight: 700; text-align: center;">
                                Halo <span style="background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"><?= esc($nama) ?></span> 👋
                            </h2>
                        </td>
                    </tr>
                    
                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <div style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%); border: 2px solid rgba(239, 68, 68, 0.3); border-radius: 16px; padding: 24px; text-align: center;">
                                <p style="margin: 0 0 16px 0; color: rgba(255,255,255,0.8); font-size: 15px; line-height: 1.6;">
                                    Pembayaran dengan nomor pendaftaran:
                                </p>
                                
                                <!-- Registration Number Box -->
                                <div style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.3) 0%, rgba(99, 102, 241, 0.3) 100%); border: 2px solid rgba(139, 92, 246, 0.4); border-radius: 12px; padding: 16px 20px; margin-bottom: 16px;">
                                    <h3 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 900; letter-spacing: 2px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                        <?= esc($no_pendaftaran) ?>
                                    </h3>
                                </div>
                                
                                <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 16px; line-height: 1.6;">
                                    <strong style="color: #ef4444; font-size: 18px; text-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);">DITOLAK</strong>.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Admin Notes -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <div style="background: rgba(251, 191, 36, 0.1); border-left: 4px solid #f59e0b; border-radius: 12px; padding: 20px;">
                                <p style="margin: 0 0 12px 0; color: #fbbf24; font-size: 14px; font-weight: 700;">
                                    📝 Catatan Admin
                                </p>
                                <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 14px; line-height: 1.6;">
                                    <?= esc($catatan) ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 30px;">
                            <div style="height: 2px; background: linear-gradient(90deg, transparent 0%, rgba(239, 68, 68, 0.5) 50%, transparent 100%);"></div>
                        </td>
                    </tr>
                    
                    <!-- Next Steps Info -->
                    <tr>
                        <td style="padding: 30px;">
                            <div style="background: rgba(99, 102, 241, 0.1); border-left: 4px solid #6366F1; border-radius: 12px; padding: 20px;">
                                <p style="margin: 0 0 8px 0; color: #a78bfa; font-size: 14px; font-weight: 600;">
                                    🔄 Langkah Selanjutnya
                                </p>
                                <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 14px; line-height: 1.6;">
                                    Silakan hubungi admin untuk upload ulang bukti pembayaran.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Contact Information -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <div style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(99, 102, 241, 0.2) 100%); border: 2px solid rgba(139, 92, 246, 0.3); border-radius: 16px; padding: 24px;">
                                <p style="margin: 0 0 20px 0; color: #ffffff; font-size: 16px; font-weight: 700; text-align: center;">
                                    📞 Hubungi Kami
                                </p>
                                
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                        <td style="padding: 12px 0;">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                <tr>
                                                    <td style="width: 40px; vertical-align: middle;">
                                                        <div style="width: 40px; height: 40px; background: rgba(139, 92, 246, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center; text-align: center; line-height: 40px;">
                                                            <span style="color: #8B5CF6; font-size: 18px;">📧</span>
                                                        </div>
                                                    </td>
                                                    <td style="padding-left: 16px; vertical-align: middle;">
                                                        <p style="margin: 0; color: rgba(255,255,255,0.6); font-size: 12px;">Email</p>
                                                        <p style="margin: 0; color: #ffffff; font-size: 14px; font-weight: 600;">
                                                            <a href="mailto:sonataviolinist@gmail.com" style="color: #8B5CF6; text-decoration: none;">sonataviolinist@gmail.com</a>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 12px 0;">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                <tr>
                                                    <td style="width: 40px; vertical-align: middle;">
                                                        <div style="width: 40px; height: 40px; background: rgba(139, 92, 246, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center; text-align: center; line-height: 40px;">
                                                            <span style="color: #8B5CF6; font-size: 18px;">📞</span>
                                                        </div>
                                                    </td>
                                                    <td style="padding-left: 16px; vertical-align: middle;">
                                                        <p style="margin: 0; color: rgba(255,255,255,0.6); font-size: 12px;">Phone / WhatsApp</p>
                                                        <p style="margin: 0; color: #ffffff; font-size: 14px; font-weight: 600;">
                                                            <a href="tel:08881785878" style="color: #8B5CF6; text-decoration: none;">0888-1785-878</a>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: rgba(15, 23, 42, 0.8); padding: 30px; text-align: center; border-top: 1px solid rgba(139, 92, 246, 0.2);">
                            <p style="margin: 0 0 16px 0; color: rgba(255,255,255,0.9); font-size: 15px; font-weight: 600;">
                                Kami siap membantu Anda! 💜
                            </p>
                            <p style="margin: 0 0 4px 0; color: rgba(255,255,255,0.7); font-size: 13px;">
                                <strong>Admin Sonata Violin</strong>
                            </p>
                            <p style="margin: 0; color: rgba(255,255,255,0.5); font-size: 12px;">
                                Email ini dikirim secara otomatis, mohon tidak membalas.
                            </p>
                            
                            <!-- Social Media Icons -->
                            <div style="margin-top: 24px;">
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tr>
                                        <td style="padding: 0 8px;">
                                            <a href="#" style="display: inline-block; width: 36px; height: 36px; background: rgba(139, 92, 246, 0.2); border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                                <span style="color: #8B5CF6; font-size: 16px;">f</span>
                                            </a>
                                        </td>
                                        <td style="padding: 0 8px;">
                                            <a href="#" style="display: inline-block; width: 36px; height: 36px; background: rgba(139, 92, 246, 0.2); border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                                <span style="color: #8B5CF6; font-size: 16px;">📷</span>
                                            </a>
                                        </td>
                                        <td style="padding: 0 8px;">
                                            <a href="#" style="display: inline-block; width: 36px; height: 36px; background: rgba(139, 92, 246, 0.2); border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                                <span style="color: #8B5CF6; font-size: 16px;">▶</span>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Copyright -->
                    <tr>
                        <td style="background: #0F172A; padding: 20px; text-align: center;">
                            <p style="margin: 0; color: rgba(255,255,255,0.4); font-size: 12px;">
                                © 2025 Sonata Violin. All rights reserved.
                            </p>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>