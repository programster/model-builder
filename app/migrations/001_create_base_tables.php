<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CreateBaseTables implements \Programster\PgsqlMigrations\MigrationInterface
{
    public function up($connectionResource): void 
    {
        $this->createModelTable();
        $this->createLogsTable();
        $this->createUserTable();
        $this->createParameterGroupTable();
        $this->createProcessTable();
        $this->createDataPointTable();
        $this->createActiveDataPointTable();
        $this->createParameterTable();
        $this->createActiveParameterTable();
        $this->createParameterHistoryTable();
        $this->createProcessHistoryTable();
        $this->createDeletedProcessTable();
        $this->createDeletedDataPointTable();
        $this->createDataPointHistoryTable();
        $this->createActiveProcessTable();
    }
    
    
    private function createActiveProcessTable() : void
    {
        $query = 
            'CREATE TABLE active_process (
                id uuid NOT NULL,
                process_id uuid UNIQUE NOT NULL REFERENCES process(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                name varchar(255) UNIQUE NOT NULL,
                description TEXT NOT NULL,
                code TEXT NOT NULL,
                output_data_point_id uuid NOT NULL REFERENCES active_data_point(data_point_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                parameter_group_id uuid NOT NULL REFERENCES parameter_group(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                hash varchar(255) NOT NULL,
                PRIMARY KEY (id)
            );
            CREATE INDEX on active_process ("process_id");
            CREATE INDEX on active_process ("output_data_point_id");
            ';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create active_process table.");
        }
    }
    
    
    private function createDataPointHistoryTable() : void
    {
        $query = 
            'CREATE TABLE data_point_history (
                id uuid NOT NULL,
                data_point_id uuid NOT NULL REFERENCES data_point(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                name varchar(255) NOT NULL,
                description TEXT NOT NULL,
                validation_schema JSON NOT NULL,
                sample_value JSON NOT NULL,
                hash varchar(255) NOT NULL,
                author_id uuid NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                PRIMARY KEY (id)
            );
            CREATE INDEX on data_point_history ("hash");
            CREATE INDEX on data_point_history ("data_point_id");
            ';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create data_point_history table.");
        }
    }
    
    
    private function createDeletedDataPointTable() : void
    {
        $query = 
            'CREATE TABLE deleted_data_point (
                id uuid NOT NULL,
                data_point_id uuid NOT NULL REFERENCES data_point(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                author_id uuid NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                deleted_at int NOT NULL,
                PRIMARY KEY (id)
            );
            CREATE INDEX on "deleted_data_point" ("data_point_id");
            ';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create deleted_data_point table.");
        }
    }
    
    
    private function createDeletedProcessTable() : void
    {
        $query = 
            'CREATE TABLE deleted_process (
                id uuid NOT NULL,
                process_id uuid NOT NULL REFERENCES "process" (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                author_id uuid NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                deleted_at int not null,
                PRIMARY KEY (id)
            );
            CREATE INDEX on deleted_process ("process_id");
            ';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create deleted_process table.");
        }
    }

    
    private function createProcessHistoryTable() : void
    {
        $query = 
            'CREATE TABLE process_history (
                id uuid NOT NULL,
                process_id uuid NOT NULL REFERENCES process(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                name varchar(255) NOT NULL,
                description TEXT not null,
                code TEXT not null,
                output_data_point_id uuid NOT NULL REFERENCES data_point(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                parameter_group_id uuid NOT NULL REFERENCES parameter_group(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                hash varchar(255) not null,
                author_id uuid NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                created_at int not null,
                PRIMARY KEY (id),
                UNIQUE (process_id, created_at)
            );
            CREATE INDEX on process_history ("process_id");
            ';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create process_history table.");
        }
    }
    
    
    private function createParameterHistoryTable() : void
    {
        $query = 
            'CREATE TABLE parameter_history (
                id uuid NOT NULL,
                parameter_id uuid NOT NULL REFERENCES parameter(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                parameter_group_id uuid NOT NULL REFERENCES parameter_group(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                name varchar(255) NOT NULL,
                test_value json,
                data_point_id uuid NOT NULL REFERENCES data_point(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                PRIMARY KEY (id),
                UNIQUE (parameter_group_id, name),
                UNIQUE (parameter_group_id, data_point_id)
            );
            ';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create parameter table.");
        }
    }
    
    
    private function createParameterTable() : void
    {
        $query = 
            'CREATE TABLE parameter (
                id uuid NOT NULL,
                PRIMARY KEY (id)
            );
            ';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create parameter table.");
        }
    }
    
    
    private function createActiveParameterTable() : void
    {
        $query = 
            'CREATE TABLE active_parameter (
                id uuid NOT NULL,
                parameter_id uuid NOT NULL REFERENCES parameter(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                parameter_group_id uuid NOT NULL REFERENCES parameter_group(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                name varchar(255) NOT NULL,
                test_value json,
                data_point_id uuid NOT NULL REFERENCES active_data_point(data_point_id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                PRIMARY KEY (id),
                UNIQUE (parameter_group_id, name),
                UNIQUE (parameter_group_id, data_point_id)
            );
            ';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create parameter table.");
        }
    }
    
    
    
    
    private function createActiveDataPointTable() : void
    {
        $query = 
            'CREATE TABLE active_data_point (
                id uuid NOT NULL,
                data_point_id uuid UNIQUE NOT NULL REFERENCES data_point(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                name varchar(255) UNIQUE NOT NULL,
                description TEXT UNIQUE NOT NULL,
                validation_schema json NOT NULL,
                sample_value json NOT NULL,
                created_at INT NOT NULL,
                hash varchar(255) NOT NULL,
                PRIMARY KEY (id)
            )';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create data_point table.");
        }
    }
    
    
    private function createParameterGroupTable() : void
    {
        $query = 
            'CREATE TABLE parameter_group (
                id uuid NOT NULL,
                author_id UUID NOT NULL REFERENCES "user" (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                created_at INT NOT NULL,
                PRIMARY KEY (id)
            )';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create data_point table.");
        }
    }
    
    
    private function createDataPointTable() : void
    {
        $query = 
            'CREATE TABLE data_point (
                id uuid NOT NULL,
                model_id uuid NOT NULL REFERENCES model(id) ON UPDATE RESTRICT ON DELETE RESTRICT,
                PRIMARY KEY (id)
            )';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create data_point table.");
        }
    }
    
    
    private function createProcessTable() : void
    {
        $query = 
            'CREATE TABLE process (
                id uuid NOT NULL,
                model_id uuid NOT NULL REFERENCES model(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                PRIMARY KEY (id)
            )';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create process table.");
        }
    }
    
    
    private function createLogsTable() : void
    {
        $enumValues = array(
            'debug',
            'info',
            'warning',
            'error',
            'critical',
            'alert',
            'emergency'
        );
        
        $wrappedElements = \Programster\CoreLibs\ArrayLib::wrapElements($enumValues, "'");
        $valuesString = implode(", ", $wrappedElements);
        $createEnumQuery = "CREATE TYPE priority_level AS ENUM ({$valuesString});";
        
        $createEnumResult = pg_query($createEnumQuery);
        
        if ($createEnumResult === false)
        {
            throw new Exception("Failed to create priority_level enum.");
        }
        
        $query = 
            'CREATE TABLE logs (
                id uuid NOT NULL,
                priority priority_level NOT NULL,
                title varchar(255) NOT NULL,
                context json NOT NULL,
                created_at int NOT NULL,
                PRIMARY KEY (id)
            )';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create logs table.");
        }
    }
    
    
    private function createModelTable() : void
    {
        $query = 
            'CREATE TABLE model (
                id uuid NOT NULL,
                name varchar(255) NOT NULL,
                description text NOT NULL,
                PRIMARY KEY (id)
            )';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create model table.");
        }
    }
    
    
    private function createUserTable() : void
    {
        $query = 
            'CREATE TABLE "user" (
                "id" uuid NOT NULL,
                "first_name" varchar(255) NOT NULL,
                "last_name" varchar(255) NOT NULL,
                "email" varchar(255) UNIQUE NOT NULL,
                "hashed_password" varchar(255) NOT NULL,
                "updated_at" int NOT NULL,
                "created_at" int NOT NULL,
                PRIMARY KEY (id)
            )';
        
        $result = pg_query($query);
        
        if ($result === false)
        {
            throw new Exception("Failed to create user table.");
        }
    }
    
    
    
    public function down($connectionResource): void 
    {
        $tables = [
            'model'
        ];
    }
}