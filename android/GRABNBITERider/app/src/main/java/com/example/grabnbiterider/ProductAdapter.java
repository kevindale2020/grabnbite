package com.example.grabnbiterider;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.List;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

public class ProductAdapter extends ArrayAdapter {
    public LayoutInflater inflater;
    public List<Product> productList;
    public Context context;

    public ProductAdapter(List<Product> productList, Context context) {
        super(context, R.layout.custom_layout2, productList);
        this.productList = productList;
        this.context = context;
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {

        inflater = LayoutInflater.from(context);

        if (inflater == null) {
            inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        }

        if (convertView == null) {
            convertView = inflater.inflate(R.layout.custom_layout2, null, true);
        }
        TextView tv_name = convertView.findViewById(R.id.tv_name);
        TextView tv_price = convertView.findViewById(R.id.tv_price);
        TextView tv_qty = convertView.findViewById(R.id.tv_qty);
        TextView tv_addons = convertView.findViewById(R.id.tv_addons);

        final Product product = productList.get(position);

        if(product.getAddons().isEmpty()) {
            tv_addons.setVisibility(View.GONE);
        } else {
            tv_addons.setText(product.getAddons());
            tv_addons.setVisibility(View.VISIBLE);
        }

        tv_name.setText(product.getName());
        tv_price.setText("₱" + String.format("%.2f", (product.getPrice() * product.getQty()) + product.getTotal()));
        tv_qty.setText(String.valueOf(product.getQty()));

        return convertView;
    }
}
