import 'package:flutter/material.dart';

class AppColors {
  // Primary Brand Colors
  static const Color primaryBlue = Color(0xFF1E40AF);    // Deep Blue
  static const Color secondaryBlue = Color(0xFF3B82F6);  // Bright Blue
  static const Color accentBlue = Color(0xFFDBEAFE);     // Soft Blue
  static const Color darkBlue = Color(0xFF1E3A8A);       // Navy

  // Background Colors
  static const Color background = Color(0xFFFFFFFF);
  static const Color surface = Color(0xFFF8FAFC);

  // Text Colors
  static const Color textPrimary = Color(0xFF1E293B);    // Slate 800
  static const Color textSecondary = Color(0xFF64748B);  // Slate 500
  static const Color textLight = Color(0xFFFFFFFF);

  // Status Colors
  static const Color success = Color(0xFF10B981); // Emerald
  static const Color warning = Color(0xFFF59E0B); // Amber
  static const Color error = Color(0xFFEF4444);   // Red
  static const Color info = Color(0xFF3B82F6);    // Blue

  // Gradients
  static const LinearGradient primaryGradient = LinearGradient(
    colors: [Color(0xFF1E40AF), Color(0xFF3B82F6)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
}
