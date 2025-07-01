@component('mail::message')
<!-- Header with Logo -->
<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #1E3A8A; padding: 20px;">
    <tr>
        <td align="center">
            <img src="{{ Storage::url('logo.png') }}" alt="Krediapl Logo" style="max-width: 150px; height: auto;" />
        </td>
    </tr>
</table>

<!-- Main Content -->
<table width="100%" cellpadding="0" cellspacing="0" style="padding: 20px; background-color: #F3F4F6;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color: #FFFFFF; border-radius: 8px; padding: 20px; border: 1px solid #E5E7EB;">
                <tr>
                    <td style="font-family: Arial, sans-serif; font-size: 24px; color: #1E3A8A; text-align: center; padding-bottom: 20px;">
                        Welcome to Krediapl, {{ $name }}!
                    </td>
                </tr>
                <tr>
                    <td style="font-family: Arial, sans-serif; font-size: 16px; color: #1F2937; line-height: 24px; padding-bottom: 20px;">
                        Your account has been created successfully. Below are your login credentials:
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #FEE2E2; border-left: 4px solid #F28C38; margin-bottom: 20px;">
                            <tr>
                                <td style="font-family: Arial, sans-serif; font-size: 16px; color: #1F2937;">
                                    <strong>Email:</strong> {{ $email }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family: Arial, sans-serif; font-size: 16px; color: #1F2937;">
                                    <strong>Password:</strong> {{ $password }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="font-family: Arial, sans-serif; font-size: 16px; color: #1F2937; line-height: 24px; padding-bottom: 20px;">
                        Please use these credentials to log in to your account. You can access the login page here:
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        @component('mail::button', ['url' => $login_url, 'color' => 'primary'])
                        <span style="background-color: #F28C38; color: #FFFFFF; padding: 10px 20px; border-radius: 4px; text-decoration: none;">
                            Login to Your Account
                        </span>
                        @endcomponent
                    </td>
                </tr>
                <tr>
                    <td style="font-family: Arial, sans-serif; font-size: 14px; color: #6B7280; line-height: 20px; padding-top: 20px;">
                        For security, we recommend changing your password after your first login.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Footer -->
<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #1E3A8A; padding: 20px;">
    <tr>
        <td align="center" style="font-family: Arial, sans-serif; font-size: 14px; color: #FFFFFF;">
            Thank you,<br>
            The Krediapl Team
        </td>
    </tr>
</table>
@endcomponent
