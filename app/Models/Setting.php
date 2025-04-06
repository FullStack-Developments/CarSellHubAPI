<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_name', 'website_icon', 'website_logo',
        'facebook_url', 'twitter_url', 'linkedin_url',
        'instagram_url', 'whatsapp_url', 'intro_images',
        'intro_keywords', 'language'
    ];
}
