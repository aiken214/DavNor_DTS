<!DOCTYPE html>
<html>
<head>
    <title>DTS QR Code Slip</title>
   <style>
@page {
        margin: 0cm 0cm;
}
    body {
        margin: 0.4cm .4cm;
        font-family: "Century Gothic", sans-serif;
    }

    .box {
        border: 1px solid black;
        padding: 2.2rem 1rem; 
        width: 19cm;
        height: 11.9cm;
        border-radius: 10px; 
        
    } 
    /* Custom Paper Size to 74mm x 105mm (or equivalent in inches: 2.91in x 4.13in  or 1/4 of A4*/
    
   
    .table {
    width: 100%;
    font-size: 0.9rem;
   
    border-collapse: collapse; /* Ensures the borders collapse */
    border-spacing: 0; /* Removes any default space between table cells */
}

.table td, .table th {
    padding: 0.7rem;
    border: 0.5px solid black; 
    border-collapse: collapse;
}

.table th {
    font-weight: bold; /* Optional for header cells */
}


    </style>
</head>
<body>
    <div>
        <div class="box">  
            
            
            <div>
                <div  style="text-align: center; margin-top: 1.5rem; margin-bottom: 1rem; font-size:.8rem;"> 
                    @if(isset($systemSetting))
              <div style="text-transform: uppercase;"> {{ $systemSetting->organization->name }} </div>
                    @endif
                    DOCUMENT TRACKING SYSTEM 
                </div>
                <table>
                    <tr>
                        <td style="width: 180px;">
                            <div style="text-align: center; margin-top:0.4rem; font-size: .7rem;">Tracking QR Code</div>
                            <div style="text-align: center; margin-top: .4rem;"> <img src="data:image/png;base64, {{ base64_encode(QrCode::size(80)->generate($document->tracking_code )) }} "></div>
                            <div style="text-align: center; margin-top:0.4rem; font-size: .8rem;">{{ $document->tracking_code }}</div>
                        </td>
                        <td>
                            <table class="table">
                
                                <tr>
                                    <td class="half-left"> Doc Type </td>
                                    <td>{{ $document->docType->description ?? '' }}</td>
                                </tr>
                                <tr style="margin-top:20;">
                                    <td> Description </td>
                                    <td>{{ $document->description ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td> From  </td>
                                    <td>{{ $document->fromUser->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td> Section/Station</td>
                                    <td>{{ $document->fromSection->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td> Date Created </td>
                                    <td>@dateDateTime($document->created_at)</td>
                                </tr>
                                <tr>
                                    <td> Actions Needed </td>
                                    <td>{{ $document->actions_needed }}</td>
                                </tr>
                                
                                
                            </table>      

                        </td>
                    </tr>
                </table>
        
           
           
          
         <div style="margin-top: 1.2rem; margin-left: 50px; font-size: .8rem;">
            Remarks: 
         </div>
            </div> 

            </div>
           
                  
            
         </div>
    </div>
   
</body>
</html>
