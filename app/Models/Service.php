<?php
declare(strict_types=1);
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Service extends Model
{
 protected $fillable = ["name", "price", "description", "status"];
 protected function casts(): array
 {
 return [
 "status" => "boolean",
 "price" => "integer",
 ];
 }
 /**
 * @return HasMany<Subscription, $this>
 */
 public function subscriptions(): HasMany
 {
 return $this->hasMany(Subscription::class);
 }
}