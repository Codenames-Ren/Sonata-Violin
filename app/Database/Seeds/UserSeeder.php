
<?php
   
   namespace App\Database\Seeds;
   
   use CodeIgniter\Database\Seeder;
   
   class UserSeeder extends Seeder
   {
       public function run()
       {
           // Data untuk Admin
           $data = [
               [
                   'username' => 'admin',
                   'password' => password_hash('admin123', PASSWORD_BCRYPT),
                   'role'     => 'admin',
                   'name'     => 'Administrator',
                   'email'    => 'admin@sonataviolin.com'
               ],
               [
                   'username' => 'operator',
                   'password' => password_hash('operator123', PASSWORD_BCRYPT),
                   'role'     => 'operator',
                   'name'     => 'Operator',
                   'email'    => 'operator@sonataviolin.com'
               ],
               [
                   'username' => 'instruktur',
                   'password' => password_hash('instruktur123', PASSWORD_BCRYPT),
                   'role'     => 'instruktur',
                   'name'     => 'Instruktur',
                   'email'    => 'instruktur@sonataviolin.com'
               ]
           ];
   
           // Insert data
           $this->db->table('users')->insertBatch($data);
       }
   }
