<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    if (!Schema::hasTable('locations')) {
      Schema::create('locations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users');
        $table->decimal('latitude', 11, 8);
        $table->decimal('longitude', 11, 8);
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Eliminar solo si la tabla está vacía
    if (Schema::hasTable('locations')) {
      $count = DB::table('locations')->count();
      if ($count === 0) {
        Schema::dropIfExists('locations');
      }
    }
  }
};
