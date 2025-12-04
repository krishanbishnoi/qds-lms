 <div class="table-responsive">
                        <table
                            style="background-image: url('{{ asset('front/img/backgroundimage.png') }}'); background-repeat: no-repeat;width: 100%;background-position: left top;background-size: cover;padding: 0px 32px 32px 32px;">
                            <tr>
                                <td
                                    style="padding-top: 60px;padding-left: 20px;font-size: 35px;font-weight:700;color: #ed1c24;">
                                    <div style="font-family: 'Sans-Serif';text-transform:uppercase;">
                                        Certificate</div>
                                </td>
                                <td align="right" style="padding-top: 30px;padding-right: 25px;">
                                    <img src="{{ asset('lms-img/creditsaison-logo.svg') }}" alt="logo" width="170"
                                        height="89">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"
                                    style="font-size: 16px;font-weight: 700;text-transform: uppercase;padding-left: 6px;color: #323232;padding-top: 40px;">
                                    of achievement COC</td>
                            </tr>
                            <tr align="center">
                                <td colspan="2" width="100%"
                                    style="text-transform: uppercase;font-size: 16px;font-weight: 700;color: #2c2c2c;font-family: sans-serif;font-size: 12px;    font-weight: 500;padding-top: 30px;">
                                    proudly presented to :
                                </td>

                            </tr>
                            <tr style="text-align: center;">
                                <td colspan="2"
                                    style="font-weight: 800;font-family: 'Sans-Serif';font-size: 35px;color: #ed1c24;padding-top: 20px;">
                                    <b>{{ Auth::user()->fullname }}</b>
                                    <p
                                        style="padding-top: 20px; font-family: sans-serif;color: #5c5a59;font-size: 12px;font-weight: 500;margin-top: 20px;width: 70%;margin: auto;padding-bottom: 40px;">
                                        This certificate acknowledges that <strong>{{ Auth::user()->fullname }}</strong>
                                        has
                                        successfully completed
                                        the digital training program
                                        <strong>Soft Skills</strong> on
                                        <strong>{{ today()->format('d-M-Y') }}</strong>,
                                        delivered via the LMS
                                        platform at QDegrees.
                                        <br><br>
                                        It is awarded in recognition of the learner’s active participation and completion of
                                        the
                                        required
                                        training content.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="padding-bottom: 80px;">
                                    <span
                                        style="display: grid;text-align: center;font-family: sans-serif;font-size: 13px;font-weight: normal;color: #5c5a59;">Date<br>
                                        <b
                                            style="font-weight: 500;font-size: 16px;color: #474645;">{{ today()->format('d-M-Y') }}</b></span>
                                </td>
                                <td width="50%" style="padding-bottom: 80px;">
                                    <span
                                        style="display: grid;text-align: center;font-family: sans-serif;font-size: 13px;font-weight: normal;color: #5c5a59;">
                                        Manager<br>Training & Development<br>
                                </td>
                            </tr>
                        </table>
                    </div>