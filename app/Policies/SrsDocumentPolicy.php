<?php

namespace App\Policies;

use App\Models\SrsDocument;
use App\Models\User;

/**
 * SrsDocumentPolicy
 * 
 * Defines authorization rules for Software Requirements Specification (SRS) documents.
 * 
 * Authorization Rules:
 * - Users can view/edit/delete only their own SRS documents
 * - Unauthorized users cannot access other users' SRS documents
 * 
 * @package App\Policies
 */
class SrsDocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     * All authenticated users can view the SRS document list.
     *
     * @param User $user The authenticated user
     * @return bool Always true for authenticated users
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Users can only view their own SRS documents.
     *
     * @param User $user The authenticated user
     * @param SrsDocument $srsDocument The SRS document to view
     * @return bool True if user is the document owner
     */
    public function view(User $user, SrsDocument $srsDocument): bool
    {
        return $user->id === $srsDocument->user_id;
    }

    /**
     * Determine whether the user can create models.
     * All authenticated users can create new SRS documents.
     *
     * @param User $user The authenticated user
     * @return bool Always true for authenticated users
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * Users can only update their own SRS documents.
     *
     * @param User $user The authenticated user
     * @param SrsDocument $srsDocument The SRS document to update
     * @return bool True if user is the document owner
     */
    public function update(User $user, SrsDocument $srsDocument): bool
    {
        return $user->id === $srsDocument->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * Users can only delete their own SRS documents.
     *
     * @param User $user The authenticated user
     * @param SrsDocument $srsDocument The SRS document to delete
     * @return bool True if user is the document owner
     */
    public function delete(User $user, SrsDocument $srsDocument): bool
    {
        return $user->id === $srsDocument->user_id;
    }
}
