<?php
// functions/func_eta_travel.php

/**
 * Insert new ETA / Locator application
 * @param PDO $pdo
 * @param array $data
 * @return array
 */
function addEtaLocator(PDO $pdo, array $data): array {
    try {
        if(empty($data['applicationType']) || empty($data['destination'])){
            return ['success'=>false,'message'=>'Please fill in all required fields.'];
        }

        // ETA validation
        if($data['applicationType']==='ETA' && empty($data['travelDate'])){
            return ['success'=>false,'message'=>'Please select ETA travel date'];
        }

        
        if($data['applicationType']==='Locator'){
            if(empty($data['intended_departure']) || empty($data['intended_arrival'])){
                return ['success'=>false,'message'=>'Please fill in Locator times'];
            }
        }
         

        $sql = "INSERT INTO eta_locator 
                (EmpNo, application_type, travel_date, arrival_date, destination, business_type, other_purpose, travel_detail, status, date_filed, intended_departure, intended_arrival)
                VALUES
                (:EmpNo, :application_type, :travel_date, :travel_arrival, :destination, :business_type, :other_purpose, :travel_detail, 'Pending', NOW(), :intended_departure, :intended_arrival)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':EmpNo'=>$data['EmpNo'],
            ':application_type'=>$data['applicationType'],
            ':travel_date'=>$data['travelDate'] ?? null,
            ':travel_arrival'=>$data['arrivalDate'] ?? null,
            ':destination'=>$data['destination'],
            ':business_type'=>$data['businessPurpose'],
            ':other_purpose'=>$data['otherPurpose'] ?? null,
            ':travel_detail'=>$data['travelDetail'],
            ':intended_departure'=>$data['intended_departure'] ?? null,
            ':intended_arrival'=>$data['intended_arrival'] ?? null
        ]);

        return ['success'=>true,'message'=>'ETA / Locator application filed successfully!'];

    } catch (PDOException $e){
        return ['success'=>false,'message'=>'Database Error: '.$e->getMessage()];
    }
}


