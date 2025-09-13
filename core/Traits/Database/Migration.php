<?php

namespace Core\Traits\Database;

use Illuminate\Database\Schema\Blueprint;

trait Migration
{
    /**
     * Create fields common to all tables.
     */
    public function addCommonFields(Blueprint $table, bool $hasSoftDelete = false): void
    {
        $table->id();
        $table->timestamps();

        if ($hasSoftDelete) {
            $table->softDeletes();
        }
    }

    /**
     * Create fields common to seo.
     */
    public function addSeoFields(Blueprint $table): void
    {
        $table->string('seo_title', 60)->nullable();
        $table->text('seo_description')->nullable();
    }

    /**
     * Create fields common to seo.
     */
    public function addAvatar(Blueprint $table): void
    {
        $table->string('avatar', 2048)->nullable();
    }

    public function addLogo(Blueprint $table): void
    {
        $table->string('logo')->nullable();
    }

    public function addImage(Blueprint $table): void
    {
        $table->string('image_path', 2048)->nullable();
    }

    /**
     * Create fields common to seo.
     */
    public function addTeamField(Blueprint $table): void
    {
        $table->foreignId('team_id')
            ->index()
            ->constrained('teams')
            ->cascadeOnDelete();
    }

    public function addCategoryField(Blueprint $table): void
    {
        $table->foreignId('category_id')->nullable()->index()->constrained('categories');
    }

    public function addEventField(Blueprint $table): void
    {
        $table->foreignId('event_id')
            ->index()
            ->nullable()
            ->constrained('events')
            ->cascadeOnDelete();
    }

    public function addModelField(Blueprint $table): void
    {
        $table->foreignUlid('model_id')->nullable()->index()->references('id')->on('models')->onDelete('cascade');
    }

    public function addCurrencyField(Blueprint $table): void
    {
        $table->foreignUlid('currency_id')
            ->index()
            ->nullable()
            ->constrained('currencies')
            ->cascadeOnDelete();
    }

    public function addOrderField(Blueprint $table): void
    {
        $table->string('order_id')->nullable()->index();
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    }

    public function addTicketField(Blueprint $table): void
    {
        $table->foreignUlid('ticket_id')->nullable()->references('id')->on('tickets')->onDelete('cascade');
    }

    public function addProductField(Blueprint $table): void
    {
        $table->foreignUlid('product_id')->nullable()->references('id')->on('products')->onDelete(
            'cascade'
        );
    }

    public function addUserField(Blueprint $table): void
    {
        $table->foreignId('user_id')
            ->index()
            ->constrained('users')
            ->cascadeOnDelete();
    }

    public function addPaymentField(Blueprint $table): void
    {
        $paymentMethods = [
            'stripe_id'    => 'stripes',
            'klarna_id'    => 'klarnas',
            'paypal_id'    => 'paypals',
            'sofort_id'    => 'soforts',
            'transfer_id'  => 'transfers',
            'notch_pay_id' => 'notch_pays',
        ];

        foreach ($paymentMethods as $column => $tableName) {
            $table->foreignId($column)
                ->nullable()
                ->after('team_id')
                ->index()
                ->constrained($tableName)
                ->nullOnDelete();
        }
    }


    public function addLocationField(Blueprint $table): void
    {
        $table->foreignId('country_id')
            ->nullable()
            ->index()
            ->constrained('world_countries')
            ->nullOnDelete();


        $table->foreignId('division_id')
            ->nullable()
            ->index()
            ->constrained('world_divisions')
            ->nullOnDelete();

        $table->foreignId('city_id')
            ->nullable()
            ->index()
            ->constrained('world_cities')
            ->nullOnDelete();
    }

    public function dropLocationField(Blueprint $table): void
    {
        $table->dropConstrainedForeignId('country_id');
        $table->dropConstrainedForeignId('division_id');
        $table->dropConstrainedForeignId('city_id');
    }
}
