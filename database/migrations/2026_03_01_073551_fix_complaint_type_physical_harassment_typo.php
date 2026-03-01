<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix typo: 'Physical Harrasments' -> 'Physical Harassment' in complaint_type enum.
     * SQLite requires table rebuild to change CHECK constraints.
     */
    public function up(): void
    {
        // Update existing data first
        DB::table('complaints')
            ->where('complaint_type', 'Physical Harrasments')
            ->update(['complaint_type' => 'Physical Harassment']);

        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            DB::statement('
                CREATE TABLE complaints_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    transaction_no VARCHAR NOT NULL,
                    incident_date DATE NOT NULL,
                    incident_time TIME NOT NULL,
                    defendant_name VARCHAR NOT NULL,
                    defendant_address VARCHAR NOT NULL,
                    level_urgency VARCHAR NOT NULL CHECK (level_urgency IN (\'Low\', \'Medium\', \'High\')) DEFAULT \'Medium\',
                    complaint_type VARCHAR NOT NULL CHECK (complaint_type IN (\'Community Issues\', \'Physical Harassment\', \'Neighbor Dispute\', \'Money Problems\', \'Misbehavior\', \'Others\')),
                    complaint_statement TEXT NOT NULL,
                    status VARCHAR NOT NULL CHECK (status IN (\'Pending\', \'In Progress\', \'Completed\')) DEFAULT \'Pending\',
                    date_completed TIMESTAMP NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ');

            DB::statement('
                INSERT INTO complaints_new (id, user_id, transaction_no, incident_date, incident_time, defendant_name, defendant_address, level_urgency, complaint_type, complaint_statement, status, date_completed, created_at, updated_at)
                SELECT id, user_id, transaction_no, incident_date, incident_time, defendant_name, defendant_address, level_urgency, complaint_type, complaint_statement, status, date_completed, created_at, updated_at
                FROM complaints
            ');

            DB::statement('DROP TABLE complaints');
            DB::statement('ALTER TABLE complaints_new RENAME TO complaints');

            // Recreate unique index on transaction_no
            DB::statement('CREATE UNIQUE INDEX complaints_transaction_no_unique ON complaints (transaction_no)');

            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            // For MySQL/PostgreSQL, just alter the column
            Schema::table('complaints', function (Blueprint $table) {
                $table->enum('complaint_type', ['Community Issues', 'Physical Harassment', 'Neighbor Dispute', 'Money Problems', 'Misbehavior', 'Others'])->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('complaints')
            ->where('complaint_type', 'Physical Harassment')
            ->update(['complaint_type' => 'Physical Harrasments']);

        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            DB::statement('
                CREATE TABLE complaints_old (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    transaction_no VARCHAR NOT NULL,
                    incident_date DATE NOT NULL,
                    incident_time TIME NOT NULL,
                    defendant_name VARCHAR NOT NULL,
                    defendant_address VARCHAR NOT NULL,
                    level_urgency VARCHAR NOT NULL CHECK (level_urgency IN (\'Low\', \'Medium\', \'High\')) DEFAULT \'Medium\',
                    complaint_type VARCHAR NOT NULL CHECK (complaint_type IN (\'Community Issues\', \'Physical Harrasments\', \'Neighbor Dispute\', \'Money Problems\', \'Misbehavior\', \'Others\')),
                    complaint_statement TEXT NOT NULL,
                    status VARCHAR NOT NULL CHECK (status IN (\'Pending\', \'In Progress\', \'Completed\')) DEFAULT \'Pending\',
                    date_completed TIMESTAMP NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ');

            DB::statement('
                INSERT INTO complaints_old (id, user_id, transaction_no, incident_date, incident_time, defendant_name, defendant_address, level_urgency, complaint_type, complaint_statement, status, date_completed, created_at, updated_at)
                SELECT id, user_id, transaction_no, incident_date, incident_time, defendant_name, defendant_address, level_urgency, complaint_type, complaint_statement, status, date_completed, created_at, updated_at
                FROM complaints
            ');

            DB::statement('DROP TABLE complaints');
            DB::statement('ALTER TABLE complaints_old RENAME TO complaints');
            DB::statement('CREATE UNIQUE INDEX complaints_transaction_no_unique ON complaints (transaction_no)');

            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            Schema::table('complaints', function (Blueprint $table) {
                $table->enum('complaint_type', ['Community Issues', 'Physical Harrasments', 'Neighbor Dispute', 'Money Problems', 'Misbehavior', 'Others'])->change();
            });
        }
    }
};
