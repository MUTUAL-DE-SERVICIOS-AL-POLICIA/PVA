<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model {
	public $timestamps = true;
	public $guarded = ['id'];
	protected $fillable = ['name', 'shortened'];

	public function document() {
		return $this->hasMany(Document::class);
	}
}
