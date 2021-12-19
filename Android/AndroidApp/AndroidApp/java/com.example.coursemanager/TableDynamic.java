package com.example.coursemanager;

import android.content.Context;
import android.graphics.Color;
import android.graphics.Typeface;
import android.graphics.drawable.GradientDrawable;
import android.view.Gravity;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.Locale;

public class TableDynamic {
    private TableLayout tableLayout;
    private Context con;
    private String[] header;
    private JSONArray data;
    private TableRow tableRow;
    private TextView txtCell;

    public TableDynamic(TableLayout tableLayout, Context context, String[] header, JSONArray data) {
        this.tableLayout = tableLayout;
        this.con = context;
        this.header = header;
        this.data = data;
    }

    private void newRow() {
        tableRow = new TableRow(this.con);
    }

    private void newCell() {
        txtCell = new TextView(this.con);
        txtCell.setGravity(Gravity.CENTER);
        txtCell.setTextSize(25);
    }

    public void createDataTable() throws JSONException {
        tableLayout.removeAllViews();

        int indexC = 0;
        int indexR = 0;

        newRow();
        while (indexC < this.header.length) {
            newCell();

            txtCell.setText(header[indexC]);
            txtCell.setTextSize(17);
            txtCell.setTypeface(null, Typeface.BOLD);

            tableRow.setBackground(textViewColor(Color.YELLOW));
            tableRow.addView(txtCell, tableRowParams());

            indexC++;
        }
        tableLayout.addView(tableRow);

        indexC = 0;
        indexR = 0;
        while (indexR < data.length()) {
            newRow();
            while (indexC < header.length) {
                newCell();
                JSONObject row = data.getJSONObject(indexR);
                String result = row.getString(header[indexC]);
                txtCell.setText(result);
                txtCell.setTextSize(17);
                tableRow.addView(txtCell, tableRowParams());
                indexC++;

            }
            if (indexR % 2 == 0) {
                tableRow.setBackground(textViewColor(Color.parseColor("#89C9FB")));
            } else {
                tableRow.setBackground(textViewColor(Color.parseColor("#30A3FF")));
            }

            indexR++;
            indexC = 0;
            tableLayout.addView(tableRow);
        }
    }

    private TableRow.LayoutParams tableRowParams() {
        TableRow.LayoutParams params = new TableRow.LayoutParams();
        params.setMargins(1, 1, 1, 1);
        params.weight = 1;
        return params;
    }

    private GradientDrawable textViewColor (int color){
        GradientDrawable gd = new GradientDrawable();
        gd.setStroke(4,Color.parseColor("#F1F9FF"));
        gd.setColor(color);
        return gd;
    }
}