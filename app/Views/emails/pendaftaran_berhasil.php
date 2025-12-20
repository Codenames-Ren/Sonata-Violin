<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pendaftaran Berhasil - Sonata Violin</title>
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
                    
                    <!-- Success Badge -->
                    <tr>
                        <td style="padding: 30px 30px 20px 30px; text-align: center;">
                            <div style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 12px 32px; border-radius: 50px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                <span style="color: #ffffff; font-size: 14px; font-weight: 700; letter-spacing: 0.5px;">
                                    ✓ PENDAFTARAN BERHASIL
                                </span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Greeting -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <h2 style="margin: 0 0 16px 0; color: #ffffff; font-size: 24px; font-weight: 700; text-align: center;">
                                Halo <span style="background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"><?= esc($nama) ?></span> 👋
                            </h2>
                            <p style="margin: 0; color: rgba(255,255,255,0.8); font-size: 16px; line-height: 1.6; text-align: center;">
                                Pendaftaran kursus Anda di <strong style="color: #ffffff;">Sonata Violin</strong> telah berhasil.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 30px;">
                            <div style="height: 2px; background: linear-gradient(90deg, transparent 0%, rgba(139, 92, 246, 0.5) 50%, transparent 100%);"></div>
                        </td>
                    </tr>
                    
                    <!-- Registration Details -->
                    <tr>
                        <td style="padding: 30px;">
                            
                            <!-- Registration Number Highlight -->
                            <div style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(99, 102, 241, 0.2) 100%); border: 2px solid rgba(139, 92, 246, 0.3); border-radius: 16px; padding: 20px; margin-bottom: 24px; text-align: center;">
                                <p style="margin: 0 0 8px 0; color: rgba(255,255,255,0.7); font-size: 13px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                    No. Pendaftaran
                                </p>
                                <h3 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 900; letter-spacing: 2px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                    <?= esc($no_pendaftaran) ?>
                                </h3>
                            </div>
                            
                            <!-- Detail Table -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: rgba(15, 23, 42, 0.5); border-radius: 12px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid rgba(139, 92, 246, 0.1);">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="color: rgba(255,255,255,0.6); font-size: 14px; font-weight: 500; width: 40%;">
                                                    📝 Nama
                                                </td>
                                                <td style="color: #ffffff; font-size: 14px; font-weight: 600; text-align: right;">
                                                    <?= esc($nama) ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid rgba(139, 92, 246, 0.1);">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="color: rgba(255,255,255,0.6); font-size: 14px; font-weight: 500; width: 40%;">
                                                    📦 Paket
                                                </td>
                                                <td style="color: #ffffff; font-size: 14px; font-weight: 600; text-align: right;">
                                                    <?= esc($nama_paket) ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid rgba(139, 92, 246, 0.1);">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="color: rgba(255,255,255,0.6); font-size: 14px; font-weight: 500; width: 40%;">
                                                    🎯 Batch
                                                </td>
                                                <td style="color: #ffffff; font-size: 14px; font-weight: 600; text-align: right;">
                                                    <?= esc($batch) ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(99, 102, 241, 0.15) 100%);">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="color: rgba(255,255,255,0.6); font-size: 14px; font-weight: 500; width: 40%;">
                                                    💰 Harga
                                                </td>
                                                <td style="color: #10b981; font-size: 18px; font-weight: 900; text-align: right; text-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);">
                                                    Rp <?= number_format($harga, 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 30px;">
                            <div style="height: 2px; background: linear-gradient(90deg, transparent 0%, rgba(139, 92, 246, 0.5) 50%, transparent 100%);"></div>
                        </td>
                    </tr>
                    
                    <!-- Status Info -->
                    <tr>
                        <td style="padding: 30px;">
                            <div style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.2) 0%, rgba(245, 158, 11, 0.2) 100%); border-left: 4px solid #f59e0b; border-radius: 12px; padding: 20px;">
                                <p style="margin: 0 0 12px 0; color: #fbbf24; font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    ⏳ Status Pendaftaran
                                </p>
                                <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 14px; line-height: 1.6;">
                                    Status pendaftaran Anda saat ini <strong style="color: #fbbf24;">MENUNGGU VERIFIKASI PEMBAYARAN</strong>.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Additional Info -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <div style="background: rgba(99, 102, 241, 0.1); border-radius: 12px; padding: 20px; border: 1px solid rgba(99, 102, 241, 0.2);">
                                <p style="margin: 0 0 8px 0; color: #a78bfa; font-size: 14px; font-weight: 600;">
                                    💬 Informasi Penting
                                </p>
                                <p style="margin: 0; color: rgba(255,255,255,0.8); font-size: 14px; line-height: 1.6;">
                                    Informasi jadwal kelas akan disampaikan melalui grup WhatsApp masing-masing kelas.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: rgba(15, 23, 42, 0.8); padding: 30px; text-align: center; border-top: 1px solid rgba(139, 92, 246, 0.2);">
                            <p style="margin: 0 0 16px 0; color: rgba(255,255,255,0.9); font-size: 15px; font-weight: 600;">
                                Terima kasih telah memilih Sonata Violin! 🎻
                            </p>
                            <p style="margin: 0 0 4px 0; color: rgba(255,255,255,0.7); font-size: 13px;">
                                <strong>Admin Sonata Violin</strong>
                            </p>
                            <p style="margin: 0; color: rgba(255,255,255,0.5); font-size: 12px;">
                                Email ini dikirim secara otomatis, mohon tidak membalas.
                            </p>
                            
                            <!-- Social Media Icons (Optional) -->
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