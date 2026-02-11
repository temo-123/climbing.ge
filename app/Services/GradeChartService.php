<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * GradeChartService - Handles grade conversions between different grading systems
 * 
 * Supports conversions between:
 * - French (French Sport Grading)
 * - YDS (Yosemite Decimal System)
 * - UIAA (International Union of Alpine Associations)
 */
class GradeChartService
{
    /**
     * Comprehensive grade mapping for French lead climbing to YDS
     * Note: Values with dots are properly quoted
     */
    private const FRENCH_TO_YDS = [
        // Basic/Misc grades
        '4' => '5.6',
        '5' => '5.6',
        '5a' => '5.7',
        '5a+' => '5.7',
        '5b' => '5.8',
        '5b+' => '5.8',
        '5c' => '5.9',
        '5c+' => '5.9',
        
        // 6th grade series
        '6' => '5.10a',
        '6a' => '5.10a',
        '6a+' => '5.10b',
        '6a +' => '5.10b',
        '6b' => '5.10c',
        '6b+' => '5.10d',
        '6b +' => '5.10d',
        '6c' => '5.11a',
        '6c+' => '5.11b',
        '6c +' => '5.11b',
        
        // 7th grade series
        '7' => '5.11d',
        '7a' => '5.11d',
        '7a+' => '5.12a',
        '7a +' => '5.12a',
        '7b' => '5.12b',
        '7b+' => '5.12c',
        '7b +' => '5.12c',
        '7c' => '5.12d',
        '7c+' => '5.13a',
        '7c +' => '5.13a',
        
        // 8th grade series
        '8' => '5.13b',
        '8a' => '5.13b',
        '8a+' => '5.13c',
        '8a +' => '5.13c',
        '8b' => '5.13d',
        '8b+' => '5.14a',
        '8b +' => '5.14a',
        '8c' => '5.14b',
        '8c+' => '5.14c',
        '8c +' => '5.14c',
        
        // 9th grade series
        '9' => '5.14d',
        '9a' => '5.14d',
        '9a+' => '5.15a',
        '9a +' => '5.15a',
        '9b' => '5.15b',
        '9b+' => '5.15c',
        '9b +' => '5.15c',
        '9c' => '5.15d',
        '9c+' => '5.15d',
        '9c +' => '5.15d',
        
        // Project grades
        'Project' => 'Project',
        'project' => 'Project',
        'proj' => 'Project',
        'PROJ' => 'Project',
    ];

    /**
     * Comprehensive grade mapping for French bouldering to YDS
     */
    private const FRENCH_BOULDER_TO_YDS = [
        'VB' => '4-',
        'VB-' => '4-',
        'VB+' => '4',
        'V0-' => '4',
        'V0' => '4+',
        'V0+' => '5a',
        'V1' => '5b',
        'V1+' => '5c',
        'V2' => '5c',
        'V2+' => '6a',
        'V3' => '6a',
        'V3+' => '6b',
        'V4' => '6b',
        'V4+' => '6c',
        'V5' => '6c+',
        'V5+' => '7a',
        'V6' => '7a',
        'V6+' => '7a+',
        'V7' => '7a+',
        'V7+' => '7b',
        'V8' => '7b',
        'V8+' => '7c',
        'V9' => '7c',
        'V9+' => '7c+',
        'V10' => '7c+',
        'V10+' => '8a',
        'V11' => '8a',
        'V11+' => '8a+',
        'V12' => '8a+',
        'V12+' => '8b',
        'V13' => '8b',
        'V13+' => '8b+',
        'V14' => '8b+',
        'V14+' => '8c',
        'V15' => '8c',
        'V15+' => '8c+',
        'V16' => '8c+',
        'V16+' => '9a',
        'V17' => '9a',
        'Project' => 'Project',
        'project' => 'Project',
        'proj' => 'Project',
    ];

    /**
     * Comprehensive grade mapping for French lead climbing to UIAA
     */
    private const FRENCH_TO_UIAA = [
        '4' => 'IV',
        '5' => 'IV+',
        '5a' => 'V+',
        '5a+' => 'V+',
        '5b' => 'VI-',
        '5b+' => 'VI-',
        '5c' => 'VI',
        '5c+' => 'VI',
        '6' => 'VI+',
        '6a' => 'VI+',
        '6a+' => 'VII-',
        '6a +' => 'VII-',
        '6b' => 'VII',
        '6b+' => 'VII+',
        '6b +' => 'VII+',
        '6c' => 'VII+/VIII-',
        '6c+' => 'VIII-',
        '6c +' => 'VIII-',
        '7' => 'VIII',
        '7a' => 'VIII',
        '7a+' => 'VIII+',
        '7a +' => 'VIII+',
        '7b' => 'VIII+/IX-',
        '7b+' => 'IX-',
        '7b +' => 'IX-',
        '7c' => 'IX',
        '7c+' => 'IX+',
        '7c +' => 'IX+',
        '8' => 'IX+/X-',
        '8a' => 'IX+/X-',
        '8a+' => 'X-',
        '8a +' => 'X-',
        '8b' => 'X',
        '8b+' => 'X+',
        '8b +' => 'X+',
        '8c' => 'XI-',
        '8c+' => 'XI',
        '8c +' => 'XI',
        '9' => 'XI+',
        '9a' => 'XI+',
        '9a+' => 'XII-',
        '9a +' => 'XII-',
        '9b' => 'XII',
        '9b+' => 'XII+',
        '9b +' => 'XII+',
        '9c' => 'XIII',
        '9c+' => 'XIII',
        'Project' => 'Project',
        'project' => 'Project',
        'proj' => 'Project',
    ];

    /**
     * Comprehensive grade mapping for French bouldering to UIAA
     */
    private const FRENCH_BOULDER_TO_UIAA = [
        'VB' => '4-',
        'VB-' => '4-',
        'VB+' => '4',
        'V0-' => 'VI+',
        'V0' => 'VII-',
        'V0+' => 'VII',
        'V1' => 'VII+',
        'V1+' => 'VII+/VIII-',
        'V2' => 'VII+/VIII-',
        'V2+' => 'VIII-',
        'V3' => 'VIII-',
        'V3+' => 'VIII',
        'V4' => 'VIII',
        'V4+' => 'VIII/VIII+',
        'V5' => 'VIII/VIII+',
        'V5+' => 'VIII+',
        'V6' => 'VIII+',
        'V6+' => 'IX-',
        'V7' => 'IX-',
        'V7+' => 'IX',
        'V8' => 'IX',
        'V8+' => 'IX/IX+',
        'V9' => 'IX/IX+',
        'V9+' => 'IX+',
        'V10' => 'IX+',
        'V10+' => 'X-',
        'V11' => 'X-',
        'V11+' => 'X',
        'V12' => 'X',
        'V12+' => 'X+',
        'V13' => 'X+',
        'V13+' => 'XI-',
        'V14' => 'XI-',
        'V14+' => 'XI',
        'V15' => 'XI',
        'V15+' => 'XI+',
        'V16' => 'XI+',
        'V16+' => 'XII-',
        'V17' => 'XII-',
        'Project' => 'Project',
        'project' => 'Project',
        'proj' => 'Project',
    ];

    /**
     * Convert grade from French system to target system
     *
     * @param string|null $grade The French grade to convert
     * @param string $targetSystem The target grading system (yds, uiaa)
     * @param string $climbingType Type of climbing (lead, bouldering)
     * @return string The converted grade or 'N/A' if conversion fails
     */
    public function convertFromFrench(?string $grade, string $targetSystem = 'yds', string $climbingType = 'lead'): string
    {
        // Handle null/empty grades
        if ($grade === null || $grade === '') {
            return 'N/A';
        }

        // Normalize the grade string
        $normalizedGrade = $this->normalizeGrade($grade);

        // Return early for invalid/placeholder grades
        if ($this->isInvalidGrade($normalizedGrade)) {
            return 'N/A';
        }

        // If already in target format, return as-is
        if ($this->isAlreadyInTargetFormat($normalizedGrade, $targetSystem)) {
            return $normalizedGrade;
        }

        // Get the mapping table based on climbing type and target system
        $mapping = $this->getMapping($climbingType, $targetSystem);

        // Try direct match
        if (isset($mapping[$normalizedGrade])) {
            return $mapping[$normalizedGrade];
        }

        // Try lowercase
        $lowerGrade = strtolower($normalizedGrade);
        if (isset($mapping[$lowerGrade])) {
            return $mapping[$lowerGrade];
        }

        // Try uppercase
        $upperGrade = strtoupper($normalizedGrade);
        if (isset($mapping[$upperGrade])) {
            return $mapping[$upperGrade];
        }

        // Log the failed conversion for debugging
        Log::debug('Grade conversion failed', [
            'original' => $grade,
            'normalized' => $normalizedGrade,
            'target_system' => $targetSystem,
            'climbing_type' => $climbingType
        ]);

        return 'N/A';
    }

    /**
     * Normalize a grade string for consistent matching
     *
     * @param string $grade The grade to normalize
     * @return string The normalized grade
     */
    private function normalizeGrade(string $grade): string
    {
        // Trim whitespace
        $normalized = trim($grade);

        // Remove spaces between grade and plus (e.g., "6a +" -> "6a+")
        $normalized = preg_replace('/(\d)(?:\s*)\+/', '$1+', $normalized);
        
        // Standardize multiple plus signs (e.g., "++" -> "+")
        $normalized = preg_replace('/\++/', '+', $normalized);

        return $normalized;
    }

    /**
     * Check if a grade is invalid or a placeholder
     *
     * @param string $grade The grade to check
     * @return bool True if grade is invalid
     */
    private function isInvalidGrade(string $grade): bool
    {
        $invalidGrades = ['n/a', 'na', '?', '-', '--', '---', 'null', 'undefined', 'unknown', 'none', 'tbd', 'todo', 'tba'];
        
        return in_array(strtolower($grade), $invalidGrades, true);
    }

    /**
     * Check if grade is already in the target format
     *
     * @param string $grade The grade to check
     * @param string $targetSystem The target system
     * @return bool True if already in target format
     */
    private function isAlreadyInTargetFormat(string $grade, string $targetSystem): bool
    {
        if ($targetSystem === 'yds') {
            // YDS format: starts with "5." followed by number and optional letter
            return preg_match('/^5\.\d+[a-z]?$/i', $grade) === 1;
        }
        
        if ($targetSystem === 'uiaa') {
            // UIAA format: Roman numerals with optional +/-/+
            return preg_match('/^X{0,3}(IX|IV|V?I{0,3})[\-\+]?$/i', $grade) === 1;
        }
        
        return false;
    }

    /**
     * Get the appropriate grade mapping table
     *
     * @param string $climbingType Type of climbing (lead, bouldering)
     * @param string $targetSystem Target grading system (yds, uiaa)
     * @return array The grade mapping array
     */
    private function getMapping(string $climbingType, string $targetSystem): array
    {
        if ($climbingType === 'bouldering') {
            if ($targetSystem === 'yds') {
                return self::FRENCH_BOULDER_TO_YDS;
            }
            if ($targetSystem === 'uiaa') {
                return self::FRENCH_BOULDER_TO_UIAA;
            }
        } else {
            if ($targetSystem === 'yds') {
                return self::FRENCH_TO_YDS;
            }
            if ($targetSystem === 'uiaa') {
                return self::FRENCH_TO_UIAA;
            }
        }
        
        // Default to lead climbing YDS
        return self::FRENCH_TO_YDS;
    }

    /**
     * Convert grade with automatic type detection
     *
     * @param string|null $grade The grade to convert
     * @param string $targetSystem The target grading system
     * @param string $climbingType Type of climbing (lead, bouldering, auto)
     * @return string The converted grade
     */
    public function convert(?string $grade, string $targetSystem = 'yds', string $climbingType = 'lead'): string
    {
        return $this->convertFromFrench($grade, $targetSystem, $climbingType);
    }

    /**
     * Get all available French grades
     *
     * @param string $climbingType Type of climbing (lead, bouldering, all)
     * @return array List of available grades
     */
    public function getAvailableGrades(string $climbingType = 'all'): array
    {
        $grades = [];
        
        if ($climbingType === 'all' || $climbingType === 'lead') {
            $grades = array_unique(array_merge($grades, array_keys(self::FRENCH_TO_YDS)));
        }
        
        if ($climbingType === 'all' || $climbingType === 'bouldering') {
            $grades = array_unique(array_merge($grades, array_keys(self::FRENCH_BOULDER_TO_YDS)));
        }
        
        sort($grades);
        
        return $grades;
    }

    /**
     * Validate if a grade is a valid French grade
     *
     * @param string $grade The grade to validate
     * @param string $climbingType Type of climbing
     * @return bool True if valid grade
     */
    public function isValidGrade(string $grade, string $climbingType = 'lead'): bool
    {
        $normalized = $this->normalizeGrade($grade);
        
        if ($this->isInvalidGrade($normalized)) {
            return false;
        }
        
        $mapping = $this->getMapping($climbingType, 'yds');
        
        return isset($mapping[$normalized]) || 
               isset($mapping[strtolower($normalized)]) || 
               isset($mapping[strtoupper($normalized)]);
    }
}

