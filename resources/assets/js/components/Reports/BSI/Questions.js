import _ from 'lodash';
let Questions = [
    
       
        {   
            question_no : "Section 1 : ",
            question : "Administrative Information",
            level : 0,
            header : true,
        }, {   
            question_no : "1.1",
            question : "Date", 
            level : 3,
            header : true,
            isYesNo : false,            
        }, {   
            question_no : "1.2",
            question : "Region", 
            level : 3,
            header : true,
            isYesNo : false,
        }, {   
            question_no : "1.3",
            question : "Blood Service Facility / Hospital", 
            level : 3,
            header : true,
            isYesNo : false,
        }, {   
            question_no : "1.4",
            question : "Classification", 
            level : 3,
            header : true,
            isYesNo : false,
        }, 
        {   
            question_no : "",
            question : "Information provided by:",
            level : 0,
            header : true,
            isYesNo : false,
            parent : false,
        },        
        {   
            question_no : "1.5",
            question : "Name", 
            level : 3,
            header : true,
            isYesNo : false,            
        },   
        {   
            question_no : "1.6",
            question : "Position", 
            level : 3,
            header : true,
            isYesNo : false,            
        },    
        {   
            question_no : "1.7",
            question : "Organization", 
            level : 3,
            header : true,
            isYesNo : false,            
        },    
        {   
            question_no : "1.8",
            question : "Address", 
            level : 3,
            header : true,
            isYesNo : false,            
        },    
        {   
            question_no : "1.9",
            question : "Tel. No.", 
            level : 3,
            header : true,
            isYesNo : false,            
        },    
        {   
            question_no : "1.10",
            question : "Fax No.", 
            level : 3,
            header : true,
            isYesNo : false,                
        },    
        {   
            question_no : "1.11",
            question : "Email", 
            level : 3,
            header : true,
            isYesNo : false,                
        },
        {   
            question_no : "1.12",
            question : "Period covered by report", 
            level : 3,
            header : true,
            isYesNo : false,    
        }, 
        {   
            question_no : "Section 2 : ",
            question : "Blood Donors and Blood Collection",
            level : 0,
            header : true,
            parent : true,
        },
        {   
            question_no : "2.1",
            question : "Do you use standard operating procedures (SOPs) or local written instructions for:", 
            level : 1,        
            header : true,
            parent : true,
        },
        {   
            question_no : "2.1.1",
            question : "Blood donor recruitment", 
            level : 2,  
            isYesNo : true,
        },     
        {   
            question_no : "2.1.2",
            question : "Pre-donation counselling and donor selection", 
            level : 2,      
            isYesNo : true,
        }, 
        {   
            question_no : "2.1.3",
            question : "Blood collection and donor care", 
            level : 2,      
            isYesNo : true,
        },    
        {   
            question_no : "2.1.4",
            question : "Post-donation counselling", 
            level : 2,      
            isYesNo : true,
        },
        {   
            question_no : "2.2",
            question : "Do you maintain records of the following:",
            level : 1,
            header : true,  
            parent : true,      
        },
        {   
            question_no : "2.2.1",
            question : "Blood donor recruitment",
            level : 2,
            isYesNo : true,
        },  
        {   
            question_no : "2.2.2",
            question : "Pre-donation counselling and donor selection",
            level : 2,
            isYesNo : true,
        },  
        {   
            question_no : "2.2.3",
            question : "Blood collection and donor care",
            level : 2,
            isYesNo : true,
        },  
        {   
            question_no : "2.2.4",
            question : "Post-donation counselling",
            level : 2,
            isYesNo : true,
        },  
        {   
            question_no : "2.3",
            question : "How many blood donors donated whole blood during the reporting period?",
            level : 1,
            header : true,        
        },   
        {   
            question_no : "2.3.1",
            question : "Total number of donors who donated blood",
            level : 2,
            isYesNo : false,
        },   
        {   
            question_no : "2.3.2",
            question : "Total number of voluntary non-remunerated donors who donated blood",
            level : 2,
            isYesNo : false,
        },   
        {   
            question_no : "2.4",
            question : "How many deferrals were there from whole blood donation, by types of deferral?",
            level : 1, 
            header : true,
        },   
        {   
            question_no : "2.4.1",
            question : "Permanent deferral",
            level : 2,
            isYesNo : false,
        },   
        {   
            question_no : "2.4.2",
            question : "Temporary deferral",
            level : 2,
            isYesNo : false,       
        },   
        {   
            question_no : "2.4.3",
            question : "Total number of deferrals",
            level : 2,
            isYesNo : false,
        },   
        {   
            question_no : "2.5",
            question : "How many deferrals were there from whole blood donation, by reasons for deferral?",
            level : 1,
            header : true,        
        },   
        {   
            question_no : "2.5.1",
            question : "Low haemoglobin (in the old monitoring forms this is Hemoglobin)",
            level : 2,
            isYesNo : false,
        },   
        {   
            question_no : "2.5.2",
            question : "Other medical conditions (in the old monitoring forms this is PE&Hx)",
            level : 2,
            isYesNo : false,     
        },   
        {   
            question_no : "2.5.3",
            question : "High-risk behaviour (in the old monitoring forms this is under TTDs)",
            level : 2,
            isYesNo : false,       
        },   
        {   
            question_no : "2.5.4",
            question : "Travel and other reasons (in the old monitoring forms this is under Other Reasons)",
            level : 2,
            isYesNo : false,     
        },   
        {   
            question_no : "2.5.5",
            question : "Total number of deferrals",
            level : 2,
            isYesNo : false,
        },   
        {   
            question_no : "2.6",
            question : "How many whole blood donations were collected, by types of donation?",
            level : 1,
            header : true, 
        },   
        {   
            question_no : "2.6.1",
            question : "Voluntary non-remunerated donations",
            level : 2,
            isYesNo : false,             
        },       
        {   
            question_no : "2.6.2",
            question : "Family/replacement donations",
            level : 2,
            isYesNo : false,             
        },   
        {   
            question_no : "2.6.3",
            question : "Paid donations",
            level : 2,
            isYesNo : false,       
        },       
        {   
            question_no : "2.6.4",
            question : "Total number of donations",
            level : 2,
            isYesNo : false,
        },   
        {   
            question_no : "2.7",
            question : "How many whole blood donations were collected from:",
            level : 1,
            header : true,        
        },    
        {   
            question_no : "2.7.1",
            question : "Male Donors",
            level : 2, 
            
            isYesNo : false,  
        },     
        {   
            question_no : "2.7.2",
            question : "Female Donors",
            level : 2, 
            
            isYesNo : false,
        }, 
        {   
            question_no : "2.7.3",
            question : "Total number of donations",
            level : 2, 
            
            isYesNo : false,
        },       
        {   
            question_no : "2.8",
            question : "How many whole blood donations were collected from:",
            level : 1, 
            header : true,
        },     
        {   
            question_no : "2.8.1",
            question : "Donors under 18 years",
            level : 2, 
            
            isYesNo : false,       
        },   
        {   
            question_no : "2.8.2",
            question : "Donors aged 18 to 24 years",
            level : 2, 
            
            isYesNo : false,        
        },   
        {   
            question_no : "2.8.3",
            question : "Donors aged 25 to 44 years",
            level : 2, 
            
            isYesNo : false,      
        },   
        {   
            question_no : "2.8.4",
            question : "Donors aged 45 to 64 years",
            level : 2, 
            
            isYesNo : false,        
        },   
        {   
            question_no : "2.8.5",
            question : "Donors aged 65 years or older",
            level : 2, 
            
            isYesNo : false,      
        },         
        {   
            question_no : "2.8.6",
            question : "Total number of donations",
            level : 2, 
            
            isYesNo : false,
        },     
        {   
            question_no : "2.9",
            question : "How many whole blood donations were collected from first-time voluntary non-remunerated donors?",
            level : 1, 
            
            isYesNo : false,
        },  
        {   
            question_no : "2.10",
            question : "Donation: donor ratio for voluntary non-remunerated whole blood donors",
            level : 1, 
            
            isYesNo : false,
        },                                    
        {   
            question_no : "2.11",
            question : "Are any blood donations collected though apheresis procedures?",
            level : 1, 
            
            isYesNo : true,
        },   
        {   
            question_no : "2.11.1",
            question : "Voluntary non-remunerated apheresis donations",
            level : 2, 
            
            isYesNo : false,     
        },      
        {   
            question_no : "2.11.2",
            question : "Family/ replacement apheresis donations",
            level : 2, 
            
            isYesNo : false,       
        },      
        {   
            question_no : "2.11.3",
            question : "Paid apheresis donations",
            level : 2, 
            
            isYesNo : false,         
        },      
        {   
            question_no : "2.11.4",
            question : "Others (please specify):",
            level : 2, 
            
            isYesNo : false,             
        },      
        {   
            question_no : "2.11.5",
            question : "Total number of apheresis donations",
            level : 2, 
            
            isYesNo : false,
        },      
        {   
            question_no : "2.11.6",
            question : "Total number of donors who donated through apheresis procedures during reporting period",
            level : 2, 
            
            isYesNo : false,
        },      
        {   
            question_no : "2.12",
            question : "Do you have a system for post-donation counselling of blood donors who test positive for transfusion-transmissible infections?",
            level : 1, 
            isYesNo : true,
        },       
    // section 2       

    // section 3
            {   
                question_no : "Section 3 : ",
                question : "Screening for Transfusion-Transmissible Infections",
                level : 0, 
                header : true,
                parent : true,                
            },         
            {   
                question_no : "3.1",
                question : "Do you perform laboratory screening of blood donations for transfusion transmissible infections?",
                level : 1, 
                isYesNo : true,
            },       
            {   
                question_no : "3.1.1",
                question : "Do you use EIA for screening blood donations for TTIs?",
                level : 2, 
                isYesNo : true,
            },       
            {   
                question_no : "3.2",
                question : "Do you use standard operating procedures or local written instructions for laboratory screening of blood donations for transfusion transmissible infections?",
                level : 1, 
                isYesNo : true,
            },       
            {   
                question_no : "3.3",
                question : "Do you participate in an external quality assessment scheme/external evaluation or performance for transfusion-transmissible infections?",
                level : 1, 
                isYesNo : true,
            },       
            {   
                question_no : "3.4",
                question : "How many donations (whole blood and apheresis) were screened for the following transfusion-transmissible infections?",
                level : 1,
                header : true,        
            },
            {   
                question_no : "3.4.1",
                question : "HIV",
                level : 2, 
                isYesNo : false,
            },
            {   
                question_no : "3.4.2",
                question : "Hepatitis B",
                level : 2, 
                isYesNo : false,
            },
            {   
                question_no : "3.4.3",
                question : "Hepatitis C",
                level : 2, 
                isYesNo : false,
            },
            {   
                question_no : "3.4.4",
                question : "Syphilis",
                level : 2, 
                isYesNo : false,
            },
            {   
                question_no : "3.4.5",
                question : "Malaria",
                level : 2, 
                isYesNo : false,
            },
            {   
                question_no : "3.5",
                question : "How many donations (whole blood and apheresis) were: (a) reactive in the screening test; and (b) positive in the confirmatory test?",
                level : 1,
                header : true,        
            },
            {   
                question_no : "3.5.0",
                question : "",
                level : 1,
                header : true,        
            },    
            {   
                question_no : "3.6",
                question : "What was the prevalence of the following TTI markers in donated blood during the reporting period?",
                level : 1,
                header : true,        
            },
            {   
                question_no : "3.6.1",
                question : "HIV",
                level : 2, 
                isYesNo : false,
            },
            {   
                question_no : "3.6.2",
                question : "Hepatitis B",
                level : 2, 
                isYesNo : false,
            },
            {   
                question_no : "3.6.3",
                question : "Hepatitis C",
                level : 2, 
                isYesNo : false,
            },
            {   
                question_no : "3.6.4",
                question : "Syphilis",
                level : 2, 
                isYesNo : false,
            },
            {   
                question_no : "3.6.5",
                question : "Malaria",
                level : 2, 
                isYesNo : false,
            },
            {   
                question_no : "3.7",
                question : "Details in which screening for TTIs is performed: number of donations tested, use of standard operating procedures (SOPs) and participation in external quality assessment/external evaluation of performance",
                level : 1,
                header : true,        
            },
            {   
                question_no : "3.7.1",
                question : "",
                level : 1, 
                header : true,
            },    
            {   
                question_no : "3.8",
                question : "Were any blood units issued without screening due to:",
                level : 1, 
                header : true,        
            },
            {   
                question_no : "3.8.1",
                question : "Non-availability of test kits/reagents",
                level : 2, 
                isYesNo : true,
            },
            {   
                question_no : "3.8.2",
                question : "Emergency situations",
                level : 2, 
                isYesNo : true,
            },
            {   
                question_no : "3.8.3",
                question : "Staff shortages",
                level : 2, 
                isYesNo : true,
            },
            {   
                question_no : "3.8.4",
                question : "Equipment failure/breakdown/power loss",
                level : 2, 
                isYesNo : true,
            },
            {   
                question_no : "3.8.5",
                question : "Other reasons (please specify)",
                level : 2, 
                isYesNo : true,
            },
        
    // section 3

    // section 4
        {   
            question_no : "Section 4 : ",
            question : "Blood group serology testing of blood donations",
            level : 0, 
            header : true,
            parent : true,            
        },
        {   
            question_no : "4.1",
            question : "Do you perform blood group serology testing of blood donations?",
            level : 1,
            isYesNo : true,
        },
        {   
            question_no : "4.1.1",
            question : "Do you use tube method for blood group serology testing?",
            level : 2, 
            isYesNo : true,
        },
        {   
            question_no : "4.2",
            question : "Do you use standard operating procedures or local written instructions for blood group serology testing of blood donations?",
            level : 1, 
            isYesNo : true,
        },
        {   
            question_no : "4.3",
            question : "Do you maintain records of blood group serology testing?",
            level : 1, 
            isYesNo : true,
        },
        {   
            question_no : "4.4",
            question : "Do you participate in an external quality assessment scheme/external evaluation of performance for blood serology?",
            level : 1, 
            isYesNo : true,
        },
    // section 4
    
    // section 5
        {   
            question_no : "Section 5: ",
            question : "Blood component preparation, storage and transportation",
            level : 0, 
            header : true,
            parent : true,            
        },
        {   
            question_no : "5.1",
            question : "Do you prepare blood components?",
            level : 1, 
            isYesNo : true,
        },
        {   
            question_no : "5.2",
            question : "How many whole blood donations were separated into components?",
            level : 1, 
            isYesNo : false,
        },
        {   
            question_no : "5.3",
            question : "How many units of blood components were prepared from whole blood donations?",
            level : 1,         
            header : true,
        },
        {   
            question_no : "5.3.1",
            question : "Red cell preparations",
            level : 2, 
            isYesNo : false,
        },
        {   
            question_no : "5.3.2",
            question : "Platelet concentrates",
            level : 2, 
            isYesNo : false,
        },
        {   
            question_no : "5.3.3",
            question : "Plasma",
            level : 2, 
            isYesNo : false,
        },
        {   
            question_no : "5.3.4",
            question : "Fresh Frozen Plasma",
            level : 2, 
            isYesNo : false,
        },
        {   
            question_no : "5.3.5",
            question : "Cryoprecipitate",
            level : 2, 
            isYesNo : false,
        },
        {   
            question_no : "5.4",
            question : "How many units of blood components were prepared through apheresis procedures?",
            level : 1,
            header : true,        
        },    
        {   
            question_no : "5.4.1",
            question : "Apheresis red cells",
            level : 2,             
            isYesNo : false,
        },    
        {   
            question_no : "5.4.2",
            question : "Apheresis platelets",
            level : 2,             
            isYesNo : false,
        },    
        {   
            question_no : "5.4.3",
            question : "Apheresis plasma",
            level : 2,             
            isYesNo : false,
        },    
        {   
            question_no : "5.4.4",
            question : "Total blood components",
            level : 2,             
            isYesNo : false,
        },    
        {   
            question_no : "5.5",
            question : "Do you use standard operating procedures or local written instructions for the preparation of blood components?",
            level : 1,
            isYesNo : true,
        },    
        {   
            question_no : "5.6",
            question : "Do you maintain records of blood component preparation?",
            level : 1, 
            
            isYesNo : true,
        },    
        {   
            question_no : "5.7",
            question : "Do you store whole blood and whole blood components in temperature-monitored equipment?",
            level : 1,
            isYesNo : true,
        },    
        {   
            question_no : "5.8",
            question : "Do you transport whole blood and whole blood components in temperature-monitored equipment?",
            level : 1,
            isYesNo : true,
        },    
        {   
            question_no : "5.9",
            question : "Do you store test kits and reagents in temperature-monitored equipment?",
            level : 1,
            isYesNo : true,
        },
        {   
            question_no : "5.10",
            question : "How many units of whole blood were discarded due to faulty collection?",
            level : 1,
            isYesNo : false,
        },
        {   
            question_no : "5.11",
            question : "How many units of whole blood and blood components were discarded due to other reasons?",
            level : 1,
            header : true,
        },
        {   
            question_no : "5.11.0",
            question : "",
            level : 2, 
            header : true,
        }, 
    // section 5


    // section 6
        {   
            question_no : "Section 6:",
            question : "Hospital transfusion process and clinical use of blood & blood components",
            level : 0, 
            header : true,
            parent : true,            
        },
        {   
            question_no : "6.1",
            question : "Do you perform blood transfusion?",
            level : 1, 
            isYesNo : true,
        },
        {   
            question_no : "6.2",
            question : "Do you perform compatibility testing?",
            level : 1, 
            isYesNo : true,
        },
        {   
            question_no : "6.2.1",
            question : "Do you use standard operating procedures or local written instructions for compatibility testing?",
            level : 2, 
            isYesNo : true,
        },
        {   
            question_no : "6.2.2",
            question : "Do you maintain records of compatibility testing?",
            level : 2, 
            isYesNo : true,
        },
        {   
            question_no : "6.2.3",
            question : "Do you participate in an external quality assessment scheme/external evaluation of performance for compatibility testing?",
            level : 2, 
            isYesNo : true,
        },
        {   
            question_no : "6.2.4",
            question : "Do you store whole blood and whole blood components in temperature-monitored equipment?",
            level : 2, 
            isYesNo : true,
        },
        {   
            question_no : "6.3",
            question : "Do you participate in:",
            level : 1, 
            header : true,    
            parent : true,    
        },
        {   
            question_no : "6.3.1",
            question : "Hospital transfusion committee",
            level : 2, 
            isYesNo : true,
        },
        {   
            question_no : "6.3.2",
            question : "System for monitoring clinical transfusion practice",
            level : 2, 
            isYesNo : true,
        },
        {   
            question_no : "6.3.3",
            question : "System for reporting adverse transfusion incidents",
            level : 2, 
            isYesNo : true,
        },
        {   
            question_no : "6.4",
            question : "How many patients were transfused?",
            level : 1, 
            isYesNo : false,
        },
        {   
            question_no : "6.5",
            question : "How many patients were transfused by age and gender?",
            level : 1, 
            header : true,
        }, 
        {   
            question_no : "6.5.0",
            level : 2,             
            header : true,
        },                                   
        {   
            question_no : "6.6",
            question : "How many units of each of the following blood components were transfused?",
            level : 1, 
            
            isYesNo : false,
            header : true,
        },
        {   
            question_no : "6.6.1",
            question : "Whole Blood",
            level : 2,             
            isYesNo : false,
        },    
        {   
            question_no : "6.6.2",
            question : "Red cells",
            level : 2,             
            isYesNo : false,
        },    
        {   
            question_no : "6.6.3",
            question : "Plasma and fresh frozen plasma",
            level : 2,             
            isYesNo : false,
        },    
        {   
            question_no : "6.6.4",
            question : "Platelets, whole blood-derive",
            level : 2,             
            isYesNo : false,
        },    
        {   
            question_no : "6.6.5",
            question : "Platelets, apheresis",
            level : 2,             
            isYesNo : false,
        },    
        {   
            question_no : "6.6.6",
            question : "Cryoprecipitate",
            level : 2,             
            isYesNo : false,
        },    
        {   
            question_no : "6.7",
            question : "Do you use standard operating procedures or local written instructions for the transfusion of blood to patients?",
            level : 1,              
            isYesNo : true,
        },
        {   
            question_no : "6.8",
            question : "Do you maintain records of blood transfusion to patients?",
            level : 1,             
            isYesNo : true,
        },
        {   
            question_no : "6.9",
            question : "How many serious adverse transfusion incidents or reactions were reported?",
            level : 1,
            isYesNo : false,
        },  

];       

export default {
    Questions
}