<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentCaptor extends Model
{
    protected $table      = 'developments_captors';
    public $incrementing  = false;
    public $timestamps    = true;
    
    // Definir la clave primaria como array para claves compuestas
    protected $primaryKey = null;
    
    // Definir los atributos que constituyen la clave primaria
    protected $keyType    = 'string';

    protected $fillable = [
        'devt_id',
        'user_id',
        'devtUsr_is_main',
    ];

    protected $casts = [
        'devtUsr_is_main' => 'boolean',
    ];

    /**
     * Obtener la clave primaria para el modelo
     */
    public function getKeyName()
    {
        return ['devt_id', 'user_id'];
    }

    public function development()
    {
        return $this->belongsTo(Development::class, 'devt_id', 'devt_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Sobreescribir delete para manejar claves compuestas correctamente
     */
    public function delete()
    {
        // Eliminar usando query builder en lugar del método delete() de Eloquent
        return static::query()
            ->where('devt_id', $this->devt_id)
            ->where('user_id', $this->user_id)
            ->delete();
    }

    /**
     * Sobreescribir update para manejar claves compuestas correctamente
     */
    public function update(array $attributes = [], array $options = [])
    {
        // Actualizar usando query builder en lugar del método update() de Eloquent
        if (!empty($attributes)) {
            static::query()
                ->where('devt_id', $this->devt_id)
                ->where('user_id', $this->user_id)
                ->update($attributes);
            
            // Actualizar los atributos en la instancia actual
            foreach ($attributes as $key => $value) {
                $this->attributes[$key] = $value;
            }
        }
        return true;
    }

    /**
     * Alcances (Scopes)
     */

    /**
     * Scope para obtener solo los captadores principales
     */
    public function scopeMain($query)
    {
        return $query->where('devtUsr_is_main', true);
    }

    /**
     * Scope para obtener captadores de un desarrollo específico
     */
    public function scopeByDevelopment($query, $devtId)
    {
        return $query->where('devt_id', $devtId);
    }

    /**
     * Scope para obtener developments capturados por un usuario específico
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Métodos de negocio
     */

    /**
     * Establece este captador como el principal del desarrollo
     * 
     * @return bool
     */
    public function setAsMain()
    {
        // Remover la condición de principal de otros captadores del mismo desarrollo
        self::byDevelopment($this->devt_id)
            ->where('user_id', '!=', $this->user_id)
            ->update(['devtUsr_is_main' => false]);

        // Establecer como principal
        return $this->update(['devtUsr_is_main' => true]);
    }

    /**
     * Obtiene el captador principal de un desarrollo
     * 
     * @param int $devtId
     * @return self|null
     */
    public static function getMainCaptor($devtId)
    {
        return self::byDevelopment($devtId)->main()->first();
    }

    /**
     * Verifica si un usuario es captador de un desarrollo
     * 
     * @param int $devtId
     * @param int $userId
     * @return bool
     */
    public static function isUserCaptor($devtId, $userId)
    {
        return self::where('devt_id', $devtId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Obtiene todos los captadores de un desarrollo con detalles del usuario
     * 
     * @param int $devtId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCaptorsByDevelopment($devtId)
    {
        return self::byDevelopment($devtId)
            ->with('user')
            ->get();
    }

    /**
     * Obtiene todos los developments capturados por un usuario
     * 
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getDevelopmentsByUser($userId)
    {
        return self::byUser($userId)
            ->with('development')
            ->get();
    }

    /**
     * Cuenta cuántos captadores tiene un desarrollo
     * 
     * @param int $devtId
     * @return int
     */
    public static function countCaptors($devtId)
    {
        return self::byDevelopment($devtId)->count();
    }
}
